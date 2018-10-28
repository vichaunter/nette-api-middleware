<?php
/**
 * Created by PhpStorm.
 * User: Antonin Sajboch
 * Date: 10/9/18
 * Time: 2:51 PM
 */

namespace VicHaunter\ApiMiddleware;

use Closure;
use Nuttilea\Middleware\Onion;

class ApiOnionDecorator {
    
    private $onion;
    
    public function __construct( array $inputLayers = [], array $outputLayers = [] ) {
        $this->onion = new Onion($inputLayers, $outputLayers);
    }
    
    /**
     * Add layer(s) or Onion
     *
     * @param  mixed $inputLayers
     *
     * @return Onion
     */
    public function addInputLayer( $inputLayers ) {
        return $this->onion->addInputLayer($inputLayers);
    }
    
    /**
     * Add layer(s) or Onion
     *
     * @param  mixed $outputLayers
     *
     * @return Onion
     */
    public function addOutputLayer( $outputLayers ) {
        return $this->onion->addOutputLayer($outputLayers);
    }
    
    /**
     * Run middleware around core function and pass an
     * object through it
     *
     * @param  mixed   $object
     * @param  Closure $core
     *
     * @return ApiApplicationRequest
     */
    public function handle( ApiApplicationRequest $request, Closure $core = null ) {
        if($core == null){
            $core = function(ApiApplicationRequest $request){return $request; };
        }
        return $this->onion->handle($request, $core);
    }
}
