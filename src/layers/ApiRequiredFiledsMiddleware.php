<?php

namespace VicHaunter\ApiMiddleware\Layers;

use Closure;
use VicHaunter\ApiMiddleware\ApiApplicationRequest;
use VicHaunter\ApiMiddleware\ApiLayer;

class ApiRequiredFiledsMiddleware extends ApiLayer {
    
    private $requiredFields = [];
    
    public function __construct( $requiredFields ) {
        $this->requiredFields = $requiredFields;
    }
    
    public function apiRequest( ApiApplicationRequest $request, Closure $next ) {
        if ($missingFields = $this->arrayKeysNeeded($request->getParameters(), $this->requiredFields)) {
            return $request->setError(__CLASS__.": Required fields ({$missingFields}) not set");
        }
        
        return $next($request);
    }
    
    /**
     * Checks if multiple keys exist in an array and returns non existing ones in string or null
     *
     * @param array $data
     * @param array $requiredKeys
     *
     * @return bool
     */
    private function arrayKeysNeeded( array $data, $requiredKeys ) {
        $result = array_diff(array_keys($requiredKeys), array_keys($data));
        
        return $result ? implode(',', $result) : null;
    }
}
