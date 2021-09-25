<?php
namespace App\Services;

use App\Traits\ConsumesExternalService;
use GuzzleHttp\Psr7\Request;
use Laravel\Ui\Presets\React;

class paypalservice{
    use ConsumesExternalService;
    protected $baseUri;
    protected $client_id;
    protected $client_secret;

    public function __construct()
    {
        $this->baseUri = config('services.paypal.base_uri');
        $this->client_id = config('services.paypal.client_id');
        $this->client_secret = config('services.paypal.client_secret');
    }
        public function resolveAuthorization(&$queryParams,&$formsParams,&$headers){
            $headers['Authorization'] =$this->resolveAccessToken();
        }
        public function decodeResponse(&$response){
            return json_decode($response);
        }
        public function resolveAccessToken(){
            $creditials=base64_encode("{$this->client_id}:{$this->client_secret}");
            return "basic {$creditials}"; 
        }
        public function handlePayment($request){
            $order =$this->createOrder($request->value,$request->currecncy);
            $orderlinks=collect($order->links);
            $approve=$orderlinks->where('rel','approve')->first();
            session()->put('approvalId',$order->id);
            return redirect($approve->href);
        }
        public function handleApprove(){
            if(session()->has('approvalId')){
                $approvalId=session()->get('approvalId');
                $payment=$this->capturePayment($approvalId);
                $name=$payment->payer->name->given_name;
                $payment=$payment->purchase_units[0]->payments->captures[0]->amount;
                $amount=$payment->value;
                $currency=$payment->currency_code;
                return redirect()->route('home')->withSuccess(['payment' =>"Thanks,{$name}.we received your{$amount}{$currency} payment."]);
            }
            return redirect()->route('home')->withErrors('We cannot capture the payment.try agian,please');
        }
        public function createOrder($value,$currecncy){
            return $this->makeRequest(
                'POST',
                '/v2/checkout/orders',
                [],
                [
                    'intent' => 'CAPTURE',
                    'purchase_units'=>[
                        0 => [
                            'amount'=>[
                                'currency_code'=> strtoupper($currecncy),
                                'value'=> $value
                            ]
                        ]
                    ],'application_context' =>[
                        'brand_name' => config('app.name'),
                        'shipping_preference' => 'NO_SHIPPING',
                        'user_action' => 'PAY_NOW',
                        'return_url' =>route('approval'),
                        'cancel_url' => route('cancelled')
                    ]
                ],
                [],
                $isJsonRequest=true,
            );
        }
        public function capturePayment($approvalId){
            return $this->makeRequest(
                'POST',
                "/v2/checkout/orders/{$approvalId}/capture",
                [],
                [],
                [
                    'Content-Type' => 'application/json'
                ],
            );
        }
}