<?php

namespace App\Repositories\Backoffice;
use App\Domain\Interfaces\Repositories\Backoffice\IRecordsRepository;

use App\Services\ImageUploader as UploadLogic;
use App\Models\Backoffice\Record as Model;
use App\Models\Backoffice\AvailedService;
use App\Models\Backoffice\Inventory;
use App\Models\Backoffice\Patient;
use App\Models\Backoffice\Invoice;
use App\Models\Backoffice\Item;
use App\Models\User;
use DB, Str;

class RecordsRepository extends Model implements IRecordsRepository
{

    public function fetch(){
        return self::all();
    }

    public function saveData($request){
        DB::beginTransaction();
        try {

            $record = self::find($request->id)? : new self;

            $record->vet_id = $request->vet_id;
            $record->patient_id = $request->patient_id;
            $record->pet_id = $request->pet_id;
            $record->service_id = $request->service_id;
            $record->procedure = $request->procedure;
            $record->weight = $request->weight;
            $record->notes = $request->notes;

            $record->save();

            DB::commit();

            return $record;
        } catch (\Exception $e) {
             DB::rollback();
             return false;
        }
    }

    public function findOrFail($id){
        $data = self::find($id);
        if(!$data){
            return false;
        }
        return $data;
    }

    public function deleteData($id){
        DB::beginTransaction();
        try {
            self::destroy($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function availedServices($id){
        return AvailedService::where('record_id', $id)->get();
    }

    public function saveService($request){
        DB::beginTransaction();
        try {

            $service = AvailedService::find($request->id)? : new AvailedService;

            if($request->has('record_id')){
                $service->record_id = $request->record_id;
            }
            
            $service->service_id = $request->service_id;
            $service->date = $request->date;
            $service->next_due_date = $request->next_due_date;
            
            $service->save();

            DB::commit();

            return $service;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function patientRecords($patient_id){
        $records = self::where('patient_id', $patient_id)->orderBy('created_at', 'DESC')->get();
        return $records;
    }

    public function petRecords($pet_id){
        $records = self::where('pet_id', $pet_id)->orderBy('created_at', 'DESC')->get();
        return $records;
    }

    public function vetRecords($vet_id){
        $records = self::where('vet_id', $vet_id)->orderBy('created_at', 'DESC')->get();
        return $records;
    }

    public function availedService($id){
        return AvailedService::where('id', $id)->first();
    }

    public function getItems($availed_service_id){
        return Item::where('availed_service_id', $availed_service_id)->get();
    }

    public function saveItem($request){
        DB::beginTransaction();
        try {

            $item = new Item;
            $inventory = Inventory::find($request->item_id);
            
            $item->availed_service_id = $request->availed_service_id;
            $item->item_id = $request->item_id;
            $item->quantity = $request->quantity;
            $item->price = $inventory->sale_price;
            
            $item->save();

            $inventory->stock = $inventory->stock - $request->quantity;

            $inventory->save();

            DB::commit();

            return $item;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function deleteItem($id){
        try {
            $item = Item::find($id);
            $availed_service_id = $item->availed_service_id;
            $inventory = Inventory::find($item->item_id);
            $inventory->stock = $inventory->stock + $item->quantity;
            $inventory->save();
            $item->delete();
            
            return $availed_service_id;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getRecordItems($id){
        $items = [];
        $services = $this->availedServices($id);
        foreach($services as $index => $service){
            $itemList = $this->getItems($service->id);
            if($itemList){
                foreach($itemList as $i => $item){
                    array_push($items, $item);
                }
            }
        }
        return $items;
    }

    public function fetchAllAvailedServices(){
        if(auth()->user()->type != 'patient'){
            return AvailedService::all();
        }
        $records = $this->patientRecords(auth()->user()->patient->id)->pluck('id')->toArray();
        return AvailedService::whereIn('record_id', $records);
    }

    public function generateInvoice($data){
        $total = 0;
        $description = [];

        foreach($data['services'] as $index => $service){
            $total += $service->service->price;

            $name = $service->service->name;
            $price = $service->service->price;

            //Description
            $details = $name.' for '.number_format($price,2);
            array_push($description, $details);
        }

        foreach($data['items'] as $index => $item){
            $subtotal = $item->quantity * $item->price;
            $total += $subtotal;

            $code = $item->item->item_code;
            $name = $item->item->name;
            $quantity = $item->quantity;
            $price = $item->price;

            //Description
            $details = $quantity.'x "'.$code.'|'.$name.'" for '.number_format($price, 2);
            array_push($description, $details);
        }

        // 12% tax
        $tax = $total * config('services.tax');
        //Description
        array_push($description, 'and '.(100*config('services.tax')).'% VAT');

        $total = $total + $tax;
        
        $description = implode(', ',$description);

        $invoice = Invoice::where('record_id', $data['record']->id)->where('status', 'Pending')->first();

        if(!$invoice){
            $invoice = new Invoice;
            $invoice->status = 'Pending';
            $invoice->record_id = $data['record']->id;
            $invoice->patient_id = $data['record']->patient_id;
            $invoice->invoice_number = $this->generateInvoiceNumber($data);
        }
        $invoice->amount = $total;
        $invoice->description = $description;
        $invoice->save();
    }

    public function generateInvoiceNumber($data){
        return 'VET'.date('ymd').sprintf("%05d",$data['record']->patient_id).sprintf("%03d",$data['record']->id);
    }

    public function fetchInvoices($id){
        return Invoice::where('record_id', $id)->where('status', 'Paid')->get();
    }
    
    public function getInvoice($invoiceId){
        return Invoice::where('id', $invoiceId)->first();
    }
    
    public function getInvoiceAvailedServices($invoiceId){
        return AvailedService::where('invoice_id', $invoiceId)->get();
    }
    
    public function getInvoiceRecordItems($invoiceId){
        $items = [];
        $services = $this->getInvoiceAvailedServices($invoiceId);
        foreach($services as $index => $service){
            $itemList = $this->getItems($service->id);
            if($itemList){
                foreach($itemList as $i => $item){
                    array_push($items, $item);
                }
            }
        }
        return $items;
    }

    public function getAvailedServices($start, $end){
        $start = date("Y-m-d", strtotime($start));
        $end = date("Y-m-d", strtotime($end));
        return AvailedService::where('date','>=', $start)->where('date','<=', $end)->orderBy('date', 'ASC')->get();
    }
}
