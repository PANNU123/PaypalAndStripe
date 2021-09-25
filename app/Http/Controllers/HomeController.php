<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\PaymentPlatForm;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $PaymentPlatForm=PaymentPlatForm::all();
        $currencies = Currency::all();
        return view('home',compact('currencies','PaymentPlatForm'));
    }
}
