<?php

namespace App\Http\Controllers;

use App\Services\paypalservice;
use Illuminate\Http\Request;
use  App\Resolvers\PaymentPlatFormResolvers;

class PaymentController extends Controller
{
    protected $PaymentPlatFormResolvers;
    public function __construct(PaymentPlatFormResolvers $PaymentPlatFormResolvers)
    {
        $this->middleware('auth');
        $this->PaymentPlatFormResolvers = $PaymentPlatFormResolvers;
    }
    public function pay(Request $request){
        $rules=[
            'value'=>['required','numeric','min:1'],
            'currecncy'=>['required','exists:currencies,iso'],
            'payment_platform'=>['required','exists:payment_plat_forms,id'],
        ];
        $request->validate($rules);
        $paymentPlatForm=$this->PaymentPlatFormResolvers->resolveService($request->payment_platform);
        session()->put('paymentplatformId',$request->payment_platform);
        return $paymentPlatForm->handlePayment($request);

    }

    public function Paymentapprove(){
        if(session()->has('paymentplatformId')){
            $paymentPlatForm=$this->PaymentPlatFormResolvers->resolveService(session()->get('paymentplatformId'));
            return $paymentPlatForm->handleApprove();
        }
        return redirect('home')->withErrors('We can not retrive your platform.Try again please');
        
    }
    public function Paymentcancelled(){
        return redirect('home')->withErrors('You cancelled the payment.');
    }
}
