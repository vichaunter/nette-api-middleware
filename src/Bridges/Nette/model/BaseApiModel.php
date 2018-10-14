<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 28/06/2018
 * Time: 7:32
 */

namespace VicHaunter\ApiMiddleware\Model;

class BaseApiModel {
    
    protected $repositories = [];
    
    protected $debug = false;
    
    protected $data;
//    protected $requestData;
    protected $error = ['code' => 0, 'message' => 'unknown'];
    
    protected $response;
    
    /**
     * Enable or disable debug mode for descriptive errors, saving request in filesystem, etc //TODO: implement
     *
     * @param bool $bool
     */
    public function setDebugMode( $bool = true ) {
        $this->debug = $bool ? true : false;
    }
    
    /**
     * Is only ment for setting errors and automatically handle it on response
     *
     * @param $error
     */
    public function setError( $error ) {
        $this->error = $error;
    }
    
    /**
     * Here we set the response data for return it to api model call
     * Also we check if is set $object->error from middleware, and in such case
     * we throw it and stop here the execution
     *
     * @param $response
     */
    public function setResponse( $response ) {
        $this->response = $response;
        $this->setError(false);
    }
    
    public function setRepository( $key, $repository ) {
        $this->repositories[ $key ] = $repository;
    }
    
    /**
     * Parse request data, filter it, validate and start the errors handling
     * chain. In case of error y any step will be thrown, if not, it will set
     * the filtered and allowed data to be used in api call in model with
     * $this->data
     *
     * @param $data
     */
    public function setData( $data ) {
        $this->requestData = $data;
        $this->data = $data;
    }
    
    /**
     * Send the final response after perform actions in api model call
     * Is ment for be called after the api result method.
     * It will check errors and prepare it for throw in case is some.
     *
     * @return array [data,error]
     */
//    public function result( $request ) {
//        if ($this->error != false || $this->debug === true) {
//            $result['status'] = 0;
//            if (is_array($this->error) && (isset($this->error['message']) || isset($this->error['code']))) {
//                $result['error']['message'] = isset($this->error['message']) ? $this->error['message'] : 'unknown';
//                $result['error']['code'] = isset($this->error['code']) ? $this->error['code'] : 'unknown';
//            } else {
//                $result['error']['message'] = $this->error;
//                $result['error']['code'] = 'unknown';
//            }
//        } else {
//            $result['status'] = 1;
//            $result['data'] = $this->response ? $this->response : [];
//            if (is_subclass_of($result['data'], 'Nuttilea\\EntityMapper\\Entity')) {
//                $result['data'] = $result['data']->toArray();
//            }
//            if (!is_array($result['data'])) {
//                throw new ApiException('Please, give me an array in setResponse');
//            }
//        }
//
//        //        $result['error'] = $this->error ? $this->error : null;
//        return $result;
//    }
    /**
     * Get required/not required fields in separated groups
     *
     * @param $validation
     *
     * @return mixed
     */
    public function getValidationFields( $required = false ) {
        $data['required'] = [];
        $data['noRequired'] = [];
        foreach ($this->getDataScheme() as $key => $value) {
            if (strpos($value, '!') !== false) {
                $data['required'][ $key ] = $value;
            } else {
                $data['noRequired'][ $key ] = $value;
            }
        }
        
        return $required ? $data['required'] : $data['noRequired'];
    }
    
}