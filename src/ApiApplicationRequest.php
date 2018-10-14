<?php

namespace VicHaunter\ApiMiddleware;

use Nette\Application\Request;

class ApiApplicationRequest {
    
    /** @var $netteRequest Request */
    private $netteRequest;
    private $parameters = [];
    //Response part
    public $response = [];
    public $errorMessage = null;
    public $statusCode = null;
    
    public function __construct( Request $netteRequest ) {
        $this->netteRequest = $netteRequest;
        $this->parameters = array_merge($this->netteRequest->getPost(), $this->netteRequest->getParameters());
    }
    
    public function setResponse( $response ) {
        $this->response = $response;
    }
    
    public function getResponse() {
        return $this->response;
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
    
}
