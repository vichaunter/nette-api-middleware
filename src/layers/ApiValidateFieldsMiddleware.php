<?php
/**
 * Created by PhpStorm.
 * User: vicha
 * Date: 10/10/2018
 * Time: 20:54
 */

namespace VicHaunter\Middleware\Layers;

use Closure;
use VicHaunter\ApiMiddleware\ApiApplicationRequest;
use VicHaunter\ApiMiddleware\ApiLayer;
use VicHaunter\Nette\Api\ApiException;

class ApiValidateFieldsMiddleware extends ApiLayer {
    
    /** @var ApiApplicationRequest */
    private $request;
    //
    private $requiredFields;
    private $ignorableFields;
    
    public function __construct( $requiredFields, $ignorableFields ) {
        $this->requiredFields = $requiredFields;
        $this->ignorableFields = $ignorableFields;
    }
    
    private function isBool( $value ) {
        if (is_bool($value)) {
            return true;
        }
        $value = strtolower($value);
        
        return (in_array($value, ["true", "false", "1", "0", "yes", "no"], true));
    }
    
    public function validateData( $validation, $required = false ) {
        foreach ($this->request->getParameters() as $key => $value) {
            foreach ($validation as $validKey => $validValue) {
                if ($key == $validKey) {
                    $validValue = ltrim($validValue, '!');
                    if (($required && $value == '') ||
                        ($validValue == 'int' && !is_numeric($value)) ||
                        ($validValue == 'string' && !is_string($value)) ||
                        ($validValue == 'array' && !is_array($value)) ||
                        ($validValue == 'bool' && (!is_bool($this->isBool($value)))) ||
                        ($validValue == 'float' && !is_float($value))
                    ) {
                        $this->request->setError("Wrong data set for {$key}");
                    }
                }
            }
        }
        
        return true;
    }
    
    /**
     * Check if received data match with expected types
     *
     * @param      $data
     * @param      $validation
     * @param bool $required
     */
    public function apiRequest( ApiApplicationRequest $request, Closure $next ) {
        $this->request = $request;
        try {
            if ($this->requiredFields) {
                $this->validateData($this->requiredFields, true);
            }
            if ($this->ignorableFields) {
                $this->validateData($this->ignorableFields);
            }
        } catch (ApiException $ae) {
            $request->setError(__CLASS__.": ".$ae->getMessage());
        }
        
        return $next($request);
    }
}