<?php

namespace app\controllers\api\v1;

use app\src\Application\Config\Config;
use app\src\Application\Usecases\RoleUsecase;
use app\src\Common\Loggers\Logger;
use app\src\Domain\Factories\RoleFactory;
use Exception;
use InvalidArgumentException;
use Yii;

class RoleController extends ApiController
{
    protected RoleUsecase $usecase;

    public function __construct($id, $module, Logger $log, RoleUsecase $usecase, Config $cfg, $config = [])
    {
        $this->usecase = $usecase;

        parent::__construct($id, $module, $log, $cfg, $config);
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->getBodyParams();
        var_dump($data);
        die();
        return $this->formatSuccessResponse(200, sprintf("this is role_name %s", $data['role_name']));
    }

    /**
     * @OA\Get(
     *     path="/roles/{id}",
     *     tags={"Roles"},
     *     security={{"ApiKey":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response="200", description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/RoleSuccessApiResponse")),
     *     @OA\Response(response="404", description="no rows found",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     *     )
     * 
     * @return Response
     * @throws Exception
     */
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
