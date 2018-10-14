<?php
/**
 * Created by PhpStorm.
 * User: vicha
 * Date: 12/10/2018
 * Time: 17:50
 */

namespace VicHaunter\Middleware\Layers;

use Closure;
use VicHaunter\ApiMiddleware\ApiApplicationRequest;
use VicHaunter\ApiMiddleware\ApiLayer;

class ApiSetAllowedParametersMiddleware extends ApiLayer {
    
    //
    private $requiredFields;
    private $ignorableFields;
    
    public function __construct( $requiredFields, $ignorableFields ) {
        $this->requiredFields = $requiredFields;
        $this->ignorableFields = $ignorableFields;
    }
    
    /**
     * Delete not allowed incoming attributes
     *
     * @param $data
     * @param $validation
     *
     * @return mixed
     */
    public function removeNotAllowed( $data, $validation ) {
        foreach ($data as $k => $v) {
            if (!array_key_exists($k, $validation)) {
                unset($data[ $k ]);
            }
        }
        
        return $data;
    }
    
    public function apiRequest( ApiApplicationRequest $request, Closure $next ) {
        $request->allowedParameters = $this->removeNotAllowed($request->getParameters(), array_merge($this->requiredFields, $this->ignorableFields));
        
        return $next($request);
    }
}