<?php
namespace App\Services;

use App\Traits\ConsumesExternalService;
use GuzzleHttp\Psr7\Request;
use Laravel\Ui\Presets\React;

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
           
        }
        public function handleApprove(){
            
        }
        public function createOrder($value,$currecncy){
           
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