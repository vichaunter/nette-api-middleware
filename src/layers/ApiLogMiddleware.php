<?php

namespace VicHaunter\Middleware\Layers;

use Closure;
use Nette\Utils\FileSystem;
use VicHaunter\ApiMiddleware\ApiApplicationRequest;
use VicHaunter\ApiMiddleware\ApiLayer;

class ApiLogMiddleware extends ApiLayer {
    
    private $enable = true;
    private $nameGenerator;
    private $path = __ROOTDIR__."/temp/data";
    
    public function __construct( callable $nameGenerator = null ) {
        $this->nameGenerator = $nameGenerator;
    }
    
    /**
     * @param bool $enable
     */
    public function setEnable( bool $enable ): void {
        $this->enable = $enable;
    }
    
    /**
     * @param mixed $path
     */
    public function setPath( $path ): void {
        $this->path = $path;
    }
    
    public function apiRequest( ApiApplicationRequest $request, Closure $next ) {
        if ($this->enable) {
            try {
                $fileName = time();
                if ($this->nameGenerator) {
                    $fileName = $this->nameGenerator($request);
                }
                FileSystem::createDir($this->path);
                FileSystem::write($this->path.'/'.$fileName.'.json', $request->getParameters(), null);
            } catch (\Exception $e) {
                $request->error = __CLASS__.": ".$e->getMessage();
            }
        }
        
        return $next($request);
    }
}
