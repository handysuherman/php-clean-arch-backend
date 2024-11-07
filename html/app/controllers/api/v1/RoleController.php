<?php

namespace app\controllers\api\v1;

use app\src\Application\Config\Config;
use app\src\Application\Usecases\RoleUsecase;
use app\src\Common\Constants\Exceptions\SQLExceptionMessageConstants;
use app\src\Common\Constants\HttpResponseConstants;
use app\src\Common\Constants\PaginationConstants;
use app\src\Common\Constants\QueryConstants;
use app\src\Common\DTOs\Request\Role\CreateRoleDTORequest;
use app\src\Common\DTOs\Request\Role\ListRoleDTORequest;
use app\src\Common\DTOs\Request\Role\UpdateRoleDTORequest;
use app\src\Common\Exceptions\SQLExceptions\NoRowsException;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Pagination;
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

    public function actionIndex()
    {
        try {
            $queryParams = Yii::$app->request->get();

            // For debugging purposes, you can print the parameters
            // var_dump();

            // common search queries;
            $page = Yii::$app->request->get(PaginationConstants::PAGE);
            $size = Yii::$app->request->get(PaginationConstants::SIZE);
            $search = Yii::$app->request->get(PaginationConstants::QUERY);
            $sort_by = Yii::$app->request->get(PaginationConstants::SORT_BY);
            $sort_order = Yii::$app->request->get(PaginationConstants::SORT_ORDER);
            $range_property = Yii::$app->request->get(PaginationConstants::RANGE_PROPERTY);
            $from = Yii::$app->request->get(PaginationConstants::FROM);
            $to = Yii::$app->request->get(PaginationConstants::TO);

            // additional filters
            $is_activated = Yii::$app->request->get(RoleConstants::IS_ACTIVATED);

            $pagination = new Pagination($size, $page);

            $arg = new ListRoleDTORequest();
            if ($search) {
                $arg->setQ($search);
            }

            if ($sort_by) {
                $arg->setSort_property($sort_by);
            }

            if ($sort_order) {
                $arg->setSort_order($sort_order);
            }

            if ($from) {
                $arg->setFrom($from);
            }

            if ($to) {
                $arg->setTo($to);
            }

            if ($range_property) {
                $arg->setRange_property($range_property);
            }

            if (!is_null($is_activated)) {
                $arg->setIs_activated(true);

                if ($is_activated === 'false') {
                    $arg->setIs_activated(false);
                }
            }

            $response = $this->usecase->list($this->request_context->getContext(), $arg, $pagination);

            return parent::formatSuccessResponse(200, $response);
        } catch (Exception $e) {
            return parent::formatSuccessResponse(200, $pagination->toPaginationResponse([]), $e->getMessage());
        }
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

            return parent::formatSuccessResponse(200, parent::formatUidArr($response_uid));
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
            $response = $this->usecase->getByUid($this->request_context->getContext(), Identifier::decrypt($id, $this->cfg->getKeys()->getApp_identifier_key()));
            $response->setUid(Identifier::encrypt($response->getUid(), $this->cfg->getKeys()->getApp_identifier_key()));

            return parent::formatSuccessResponse(200, RoleFactory::toKeyValArray($response), "OK");
        } catch (Exception $e) {
            return parent::formatErrorResponse($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/roles/{id}",
     *     tags={"Role"},
     *     security={{"ApiKey":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true, 
     *         @OA\JsonContent(ref="#/components/schemas/UpdateRoleParams")
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
    public function actionUpdate($id)
    {
        try {
            $data = Yii::$app->request->getBodyParams();

            $arg = new UpdateRoleDTORequest();
            $arg->setUid($id);
            $arg->setRole_name($data[RoleConstants::ROLE_NAME]);
            $arg->setDescription($data[RoleConstants::DESCRIPTION]);
            $arg->setIs_activated($data[RoleConstants::IS_ACTIVATED]);

            $response_uid = $this->usecase->update($this->request_context->getContext(), $arg);

            return parent::formatSuccessResponse(200, parent::formatUidArr($response_uid));
        } catch (NoRowsException $e) {
            if (strpos(strtolower($e->getMessage()), SQLExceptionMessageConstants::NO_ROWS_AFFECTED) !== false) {
                return parent::formatSuccessResponse(200, parent::formatUidArr($id), SQLExceptionMessageConstants::NO_ROWS_AFFECTED);
            }

            return parent::formatErrorResponse($e->getMessage());
        } catch (Exception $e) {
            return parent::formatErrorResponse($e->getMessage());
        }
    }
}
