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
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        return array_merge($this->netteRequest->getPost(), $this->netteRequest->getParameters(), $jsonRequest);
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
    
}
