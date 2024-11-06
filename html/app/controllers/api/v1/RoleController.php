<?php

namespace app\controllers\api\v1;

use app\src\Application\Config\Config;
use app\src\Application\Usecases\RoleUsecase;
use app\src\Common\Loggers\Logger;
use app\src\Domain\Factories\RoleFactory;
use Exception;
use InvalidArgumentException;

class RoleController extends ApiController
{
    protected RoleUsecase $usecase;

    public function __construct($id, $module, Logger $log, RoleUsecase $usecase, Config $cfg, $config = [])
    {
        $this->usecase = $usecase;

        parent::__construct($id, $module, $log, $cfg, $config);
    }

    public function actionView($id)
    {
        try {
            $response = $this->usecase->getByUid($this->request_context->getContext(), $id);

            return parent::formatSuccessResponse(200, RoleFactory::toKeyValArray($response), "OK");
        } catch (Exception $e) {
            return parent::formatErrorResponse($e->getMessage());
        }
    }
}
