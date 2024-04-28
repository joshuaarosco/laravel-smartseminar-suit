<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Events
use App\Events\SendEmailEvent;

//Services & Repositories
use App\Domain\Interfaces\Services\Backoffice\ICRUDService;
use App\Domain\Interfaces\Repositories\Backoffice\IRecordsRepository;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

//Global Classes
use Input, Auth;

use Xendit\Xendit;

class XenditController extends Controller
{
    //Do some magic
    public function __construct(IRecordsRepository $recordRepo, ICRUDService $CRUDservice){
        $this->data = [];
        $this->recordRepo = $recordRepo;
        $this->CRUDservice = $CRUDservice;
        $this->data['title'] = 'Xendit Payment';
        $this->currency = 'PHP';

        $this->tax = config('services.tax');
        $this->secretKey = config('services.xendit.sandbox.secret');
        // $this->secretKey = config('services.xendit.live.secret');
    }
    
    public function checkout($id){
        try {
            // ****************
            // Multiple Item Checkout
            // ****************

            $total = 0;
            $description = [];
            $checkoutItems = [];
            
            $record = $this->recordRepo->findOrFail($id);
            $items = $this->recordRepo->getRecordItems($id);
            $services = $this->recordRepo->availedServices($id);
            
            $payment_id = $record->invoice->invoice_number;

            foreach($services as $index => $service){
                $total += $service->service->price;

                $name = $service->service->name;
                $price = $service->service->price;

                //Description
                $details = $name.' for '.number_format($price,2);
                array_push($description, $details);

                //Add service on the checkout item
                array_push($checkoutItems, [
                    'name' => $name,
                    'quantity' => 1,
                    'price' => $price,
                    'category' => 'Service',
                ]);
            }

            foreach($items as $index => $item){
                $subtotal = $item->quantity * $item->price;
                $total += $subtotal;

                $code = $item->item->item_code;
                $name = $item->item->name;
                $quantity = $item->quantity;
                $price = $item->price;

                //Description
                $details = $quantity.'x "'.$code.'|'.$name.'" for '.number_format($price, 2);
                array_push($description, $details);
                
                //Add item on the checkout item
                array_push($checkoutItems, [
                    'name' => $name,
                    'quantity' => (int)$quantity,
                    'price' => $price,
                    'category' => 'Additional Items',
                ]);
            }

            // 12% tax
            $tax = $total * $this->tax;
            //Description
            array_push($description, 'and '.(100*$this->tax).'% VAT');

            //Add Tax on the checkout item
            array_push($checkoutItems, [
                'name' => 'Value Added Tax',
                'quantity' => 1,
                'price' => $tax,
                'category' => 'Tax',
            ]);

            $total = $total + $tax;
            
            $description = implode(', ',$description);

            $patient = $record->patient;

            Xendit::setApiKey($this->secretKey);
            $params = [
                'external_id' => $payment_id,
                'payer_email' => $patient->user->email,
                'description' => $description,
                'invoice_duration' => 86400,
                'customer' => [
                    'given_names' => $patient->fname,
                    'surname' => $patient->lname,
                    'email' => $patient->user->email,
                    'mobile_number' => $patient->user->contact_number
                ],
                'amount' => $total,
                'success_redirect_url' => route('backoffice.records.xendit_payment_success',['patient_id' => $patient->id, 
                                                                                            'record_id'   => $record->id, 
                                                                                            'total'		  => $total, 
                                                                                            'description' => $description,
                                                                                            'payment_id'  => $payment_id]),
                'failure_redirect_url' => route('backoffice.records.xendit_payment_failed',['patient_id'  => $patient->id, 
                                                                                            'record_id'   => $record->id, 
                                                                                            'total'		  => $total, 
                                                                                            'description' => $description,
                                                                                            'payment_id'  => $payment_id]),
                'currency' => $this->currency,
                'items' => $checkoutItems,
            ];

            $createInvoice = \Xendit\Invoice::create($params);

            return redirect($createInvoice['invoice_url']);
            
        } catch (Exception $e) {
            session()->flash('notification-status', "danger");
            session()->flash('notification-msg', $e->getMessage());
            return redirect()->back();
        }
    }
    
    public function success(Request $request){
        $record = $this->recordRepo->findOrFail($request->record_id);
        if(!$record){
            return abort(404);
        }
        $availed_services = $this->recordRepo->availedServices($record->id);
        
        $invoice = $record->invoice;
        $invoice->status = 'Paid';
        $invoice->save();

        //update availed_services invoice id
        foreach($availed_services as $index => $service){
            $service->invoice_id = $invoice->id;
            $service->save();
        }

        session()->flash('notification-status', "primary");
        session()->flash('notification-msg', 'Payment Successful!');
        return redirect()->route('backoffice.records.view', $request->record_id);
    }
    
    public function failed(Request $request){
        $record = $this->recordRepo->findOrFail($request->record_id);
        if(!$record){
            return abort(404);
        }
        session()->flash('notification-status', "danger");
        session()->flash('notification-msg', 'Payment Failed.');
        return redirect()->route('backoffice.records.invoice', $request->record_id);
    }
    
}
