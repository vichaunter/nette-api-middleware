<?php

namespace VicHaunter\Middleware\Layers;

use Closure;
use Nette\Utils\FileSystem;
use VicHaunter\ApiMiddleware\ApiApplicationRequest;
use VicHaunter\ApiMiddleware\ApiException;
use VicHaunter\ApiMiddleware\ApiLayer;

class ApiResponseCodeChecker extends ApiLayer {
    
    private $responseKeys = true;
    
    public function __construct( array $responseKeys ) {
        $this->responseKeys = $responseKeys;
    }


    
    public function apiRequest( ApiApplicationRequest $request, Closure $next ) {
        /** @var ApiApplicationRequest $response */
        $response = $next($request);
        if ($this->responseKeys) {
            $finalResponse = json_decode(json_encode($response), true);
            foreach ($finalResponse as $key => $value){
                if( !array_key_exists($value, $this->responseKeys) ){
                    throw new ApiException("Key  $value is not exists in required keys ". json_encode($this->responseKeys));
                }
            }
        }
        return $response;
    }
}
