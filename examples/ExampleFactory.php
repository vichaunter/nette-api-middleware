<?php
/**
 * Created by PhpStorm.
 * User: vicha
 * Date: 12/10/2018
 * Time: 17:01
 */

namespace VicHaunter\ApiMiddleware\Examples;

use VicHaunter\ApiMiddleware\Layers\ApiRequiredFiledsMiddleware;
use VicHaunter\ApiMiddleware\Model\BaseApiModel;
use VicHaunter\Middleware\Layers\ApiLogMiddleware;
use VicHaunter\Middleware\Layers\ApiSetAllowedParametersMiddleware;
use VicHaunter\Middleware\Layers\ApiValidateFieldsMiddleware;
use VicHaunter\Middleware\Layers\TokenAutheticationMiddleware;
use VicHaunter\Nette\Api\Layers\ApiCheckValidResponseMiddleware;

class ExampleFactory {
    
    /** @var BaseApiModel $item */
    public $baseApiModel;
    
    public $appRequest;
    
    public function __construct( BaseApiModel $baseApiModel, $appRequest ) {
        $this->baseApiModel = $baseApiModel;
        $this->appRequest = $appRequest;
    }
    
    public function runHandler() {
        $ignorableFields = $this->baseApiModel->getValidationFields();
        $requiredFields = $this->baseApiModel->getValidationFields(true);
        //
        $onion = new \VicHaunter\ApiMiddleware\ApiOnionDecorator([
            new TokenAutheticationMiddleware('tokenCode'),
            new ApiLogMiddleware(),
            new ApiRequiredFiledsMiddleware($requiredFields),
            new ApiValidateFieldsMiddleware($requiredFields, $ignorableFields),
            new ApiCheckValidResponseMiddleware($ignorableFields),
            new ApiSetAllowedParametersMiddleware($requiredFields, $ignorableFields),
        ]);
        
        return $onion->handle($this->appRequest, \Closure::fromCallable([$this->baseApiModel, 'handle']));
    }
    
}
