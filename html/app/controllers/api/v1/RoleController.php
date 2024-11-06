<?php

namespace app\controllers\api\v1;

use app\src\Application\Config\Config;
use app\src\Application\Usecases\RoleUsecase;
use app\src\Common\Constants\HttpResponseConstants;
use app\src\Common\DTOs\Request\Role\CreateRoleDTORequest;
use app\src\Common\Loggers\Logger;
use app\src\Domain\Factories\RoleFactory;
use app\src\Infrastructure\Constants\RoleConstants;
use Exception;
use Yii;

class RoleController extends ApiController
{
    protected RoleUsecase $usecase;

    public function __construct($id, $module, Logger $log, RoleUsecase $usecase, Config $cfg, $config = [])
    {
        $this->usecase = $usecase;

        parent::__construct($id, $module, $log, $cfg, $config);
    }

    /**
     * @OA\Post(
     *     path="/roles",
     *     tags={"Role"},
     *      security={{"ApiKey":{}}},
     *     @OA\RequestBody(
     *         required=true, 
     *         @OA\JsonContent(ref="#/components/schemas/CreateRoleParams")
     *     ),
     *     @OA\Response(response="200", description="create",
     *          @OA\JsonContent(ref="#/components/schemas/UidSuccessApiResponse")),
     *     @OA\Response(response="400", description="Invalid input",
     *          @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *     )
     * 
     * @return Response
     * @throws Exception
     */
    public function actionCreate()
    {
        try {
            $data = Yii::$app->request->getBodyParams();

            $arg = new CreateRoleDTORequest();
            $arg->setRole_name($data[RoleConstants::ROLE_NAME]);
            $arg->setDescription(isset($data[RoleConstants::DESCRIPTION]) ? $data[RoleConstants::DESCRIPTION] : '');

            $response_uid = $this->usecase->save($this->request_context->getContext(), $arg);

            return parent::formatSuccessResponse(200, [HttpResponseConstants::UID => $response_uid]);
        } catch (Exception $e) {
            return parent::formatErrorResponse($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/roles/{id}",
     *     tags={"Role"},
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
