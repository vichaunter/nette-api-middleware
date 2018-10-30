<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 02/03/2018
 * Time: 11:49
 */

namespace VicHaunter\ApiMiddleware\Presenters;

use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Application\IPresenter;
use Nette\Application\Responses\JsonResponse;
use VicHaunter\ApiMiddleware\ApiApplicationRequest;

class ApiBasePresenter implements IPresenter {
    
    /**
    * @return IResponse
    */
   function run( Request $request ) {
        $appRequest = new ApiApplicationRequest($request);

        $handler = 'action' . ucfirst( $request->getParameter('action') );
        $response = $this->$handler($appRequest);

        return new JsonResponse($response);
    }

}
