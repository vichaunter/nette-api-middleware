<?php

namespace VicHaunter\ApiMiddleware;

use Nette\Application\Request;
use Nette\Http\Url;

class ApiApplicationRequest implements \JsonSerializable {
    
    /** @var $netteRequest Request */
    private $netteRequest;
    private $postParameters = [];
    private $getParameters = [];
    private $inputStreamParameters = [];
    private $parameters = [];

    //Response part
    private $response = [];
    private $errorMessage = null;
    private $statusCode = 0;
    
    public function __construct( Request $netteRequest ) {
        $this->netteRequest = $netteRequest;
        $this->parameters = $this->getOriginalParameters();
    }
    
    public function setResponse( $response ) {
        $this->response = $response;
    }

    public function getError() {
        return $this->errorMessage;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getResponse() {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getOriginalParameters() {
        return array_merge($this->getGetParameters(), $this->getPostParameters(), $this->getInputStreamParameters());
    }
    
    /**
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }
    
    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function getParameter( $name ) {
        return array_key_exists($name, $this->parameters) ? $this->parameters[ $name ] : null;
    }
    
    public function setParameter($key, $value) {
        $this->parameters[$key] = $value;
    }
    
    /**
     * @return Request
     */
    public function getRequest() {
        return $this->netteRequest;
    }
    
    public function setError( $errorMessage, $statusCode = 0 ) {
        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
        
        return $this;
    }

    public function readPostParameters(){
        return $this->netteRequest->getPost();
    }

    public function readGetParameters(){
        return $this->netteRequest->getParameters();
    }

    public function readInputStreamParameters(){
        if(array_key_exists('CONTENT_TYPE', $_SERVER) && $_SERVER["CONTENT_TYPE"] === 'application/json') {
            $inputStream = file_get_contents('php://input');
            $this->inputStreamParameters = json_decode($inputStream, true);
        }
        return $this->inputStreamParameters ? $this->inputStreamParameters : [];
    }


    public function getPostParameters(){
        if(!$this->postParameters) $this->postParameters = $this->readPostParameters();
        return  $this->postParameters ? $this->postParameters : [];
    }


    public function getGetParameters(){
        if(!$this->getParameters) $this->getParameters = $this->readGetParameters();
        return  $this->getParameters ? $this->getParameters : [];
    }

    public function getInputStreamParameters(){
        return $this->readInputStreamParameters();
    }

    public function jsonSerialize() {
        $iArray['statusCode'] = $this->statusCode;
        $iArray['response'] = $this->response;
        $iArray['errorMessage'] = $this->errorMessage;
        return $iArray;
    }

}
