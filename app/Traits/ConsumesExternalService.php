<?php
namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalService{
    public function makeRequest($methods,$requestUrl,$queryParams=[],$formsParams=[],$headers=[],$isJsonRequest=false){
        $client=new Client([
            'base_uri' => $this->baseUri,
        ]);

        if(method_exists($this,'resolveAuthorization')){
            $this->resolveAuthorization($queryParams,$formsParams,$headers);
        };
        
        $response=$client->request($methods,$requestUrl,[
            $isJsonRequest ? 'json' : 'form_params' => $formsParams,
            'headers' => $headers,
            'query' => $queryParams
        ]);
        $response=$response->getBody()->getContents();
            if(method_exists($this,'decodeResponse')){
                $response=$this->decodeResponse($response);
            }
        return $response;
    }
}