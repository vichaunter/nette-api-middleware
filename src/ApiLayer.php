<?php
/**
 * Created by PhpStorm.
 * User: vicha
 * Date: 11/10/2018
 * Time: 19:22
 */

namespace VicHaunter\ApiMiddleware;

use Closure;
use Nuttilea\Middleware\LayerInterface;

abstract class ApiLayer implements LayerInterface {
    
    public function handle( $object, Closure $next ) {
        $response = $this->apiRequest($object, $next);
        // if (!$response instanceof \ApiApplictionResponse ) {
        //     throw new \Exception('Varriable $response is not instance of ApiApplicationResponse');
        // }
        return $response;
    }
    
    public abstract function apiRequest( ApiApplicationRequest $request, Closure $next );
}
