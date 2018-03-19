<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $delivereis = (new Delivery)->where('user_id',Auth::user()->id)->where('paid_status',0)->get();
        if(count($delivereis ) != 0)
            return redirect('check_out');
        $KMP = (new Settings)->where('id',1)->first()->value;
        $MP = (new Settings)->where('id',2)->first()->value;
        return view('welcome',compact('KMP','MP'));
    }

    public function checkout()
    {
        $delivereis = (new Delivery)->where('user_id',Auth::user()->id)->where('paid_status',0)->first();

        return view('home',compact('delivereis'));
    }

}
