<?php
namespace App\Services;

use App\Traits\ConsumesExternalService;
use GuzzleHttp\Psr7\Request;
use Laravel\Ui\Presets\React;
use SebastianBergmann\Environment\Console;

class stripeservice{
    use ConsumesExternalService;
    protected $baseUri;
    protected $key;
    protected $secret;

    public function __construct()
    {
        $this->baseUri = config('services.stripe.base_uri');
        $this->key = config('services.stripe.key');
        $this->secret = config('services.stripe.secret');
    }
        public function resolveAuthorization(&$queryParams,&$formsParams,&$headers){
            $headers['Authorization'] =$this->resolveAccessToken();
        }
        public function decodeResponse(&$response){
            return json_decode($response);
        }
        public function resolveAccessToken(){
           return "Bearer {$this->secret}";
        }
        public function handlePayment($request){
           $request->validate([
            'payment_method' => 'required',
           ]);
           $intent=$this->createIntent($request->value,$request->currecncy,$request->payment_method);
           session()->put('paymentIntentId',$intent->id);
           return redirect()->route('approval');
        }
        public function handleApprove(){
            if(session()->has('paymentIntentId')){
                $paymentIntentId=session()->get('paymentIntentId');
                $confirmPayment=$this->confirmPayment($paymentIntentId);
                // dd($confirmPayment);

                if($confirmPayment->status === 'requires_action'){
                    $clientSecret=$confirmPayment->client_secret;

                    return view('stripe.3d-secure')->with([
                        'clientSecret' => $clientSecret,
                    ]);
                }

                if($confirmPayment->status === 'succeeded'){
                    $name=$confirmPayment->charges->data[0]->billing_details->name;
                    $curency=strtoupper($confirmPayment->currency);
                    $amount=$confirmPayment->amount / $this->resolveFactor($curency);
                    return redirect()->route('home')->withSuccess(['payment' =>"Thanks,{$name}.we received your{$amount}{$curency} payment."]);
                }
            }
            return redirect()->route('home')->withErrors('We unable to confirm your payment.try agian,please');
        }


        public function createIntent($value,$currecncy,$paymentMethod){
            // return $paymentMethod;
            return  $this->makeRequest(
                'POST',
                '/v1/payment_intents',
                [],
                [
                    'amount' => round($value * $this->resolveFactor($currecncy)),
                    'currency' => strtolower($currecncy),
                    'payment_method' => $paymentMethod,
                    'confirmation_method' => 'manual',

                ],
            );
        }
        public function confirmPayment($paymentIntentId){
            return  $this->makeRequest(
                'POST',
                "/v1/payment_intents/{$paymentIntentId}/confirm",
            );
        }

        public function resolveFactor($currecncy){
            $zeroDecimalCurrecncies = ['JPY'];
            if(in_array($currecncy,$zeroDecimalCurrecncies)){
                return 1;
            }   
            return 100;
        }
}