<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use URL;

//Repositories

class PageController extends Controller
{
    public function __construct(){
        $this->data = [];
    }

    public function index(){
        return view('frontend.web.index',$this->data);
    }
}
