<?php
/**
 * Created by PhpStorm.
 * User: vicha
 * Date: 10/10/2018
 * Time: 21:15
 */

namespace VicHaunter\Nette\Api\Layers;

use Closure;
use VicHaunter\ApiMiddleware\ApiApplicationRequest;
use VicHaunter\ApiMiddleware\ApiLayer;
use VicHaunter\Nette\Api\ApiException;

class ApiCheckValidResponseMiddleware extends ApiLayer {
    
    private $ignorableFields;
    
    public function __construct( $ignorableFields ) {
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
        try {
            $hash = !empty($request->response['signature']) ? $request->response['signature'] : null;
            $response = json_decode(json_encode($this->removeNotAllowed($request->getParameters(), $this->ignorableFields)), true);
        } catch (ApiException $ae) {
            $request->setError(__CLASS__.": Bad request");
        }
        
        return $next($request);
    }
}