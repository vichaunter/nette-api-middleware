<?php

namespace VicHaunter\Middleware\Layers;

use Closure;
use Nette\Utils\FileSystem;
use VicHaunter\ApiMiddleware\ApiApplicationRequest;
use VicHaunter\ApiMiddleware\ApiLayer;

class ApiLogMiddleware extends ApiLayer {
    
    private $enable;
    
    public function __construct( $enable = true ) {
        $this->enable = $enable;
    }
    
    public function apiRequest( ApiApplicationRequest $request, Closure $next ) {
        if ($this->enable) {    
            try {
                FileSystem::createDir(__ROOTDIR__."/temp/data/");
                FileSystem::write(__ROOTDIR__."/temp/data/".time().'.json', $request->getParameters(), null);
            } catch (\Exception $e) {
                $request->error = __CLASS__.": ".$e->getMessage();
            }
        }
        return $next($request);
    }    
}
