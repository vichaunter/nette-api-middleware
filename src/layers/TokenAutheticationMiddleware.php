<?php

namespace VicHaunter\Middleware\Layers;

use Closure;
use VicHaunter\ApiMiddleware\ApiApplicationRequest;
use VicHaunter\ApiMiddleware\ApiLayer;

class TokenAutheticationMiddleware extends ApiLayer {
    
    private $token = null;
    
    public function __construct( $token ) {
        $this->token = $token;
    }
    
    public function apiRequest( ApiApplicationRequest $request, Closure $next ) {
        
        if ($request->getParameter("token") === $this->token) {
            return $next($request);
        }
        $request->setError('unauthorized');
        
        return $request;
    }
}
