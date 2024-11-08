<?php

namespace app\controllers\api\v1;

use app\src\Application\Config\Config;
use app\src\Application\Usecases\RoleUsecase;
use app\src\Common\Constants\Exceptions\SQLExceptionMessageConstants;
use app\src\Common\Constants\HttpConstants;
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

// TODO: metadata search api for search detailed search properties
class RoleController extends ApiController
{
    protected RoleUsecase $usecase;

    public function __construct($id, $module, Logger $log, RoleUsecase $usecase, Config $cfg, $config = [])
    {
        $this->usecase = $usecase;

        parent::__construct($id, $module, $log, $cfg, $config);
    }

    /**
     * @OA\Get(
     *     path="/roles",
     *     tags={"Role"},
     *     security={{"ApiKey":{}}},
     *     description="Please refer to RoleListSuccessApiResponse schema for detailed information on the response body",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", example="mock role name", default=""),
     *         description="search text of the list, the default value should be empty string"
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", example="mock role property", default="created_at"),
     *         description="please refer to /metadata api to see list of available metadata for this properties (sort_able_properties)"
     *     ),
     *     @OA\Parameter(
     *         name="sort_order",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", example="desc", default="desc"),
     *         description="the value is either desc or asc"
     *     ),
     *     @OA\Parameter(
     *         name="range_by",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", example="mock range properties", default=null),
     *         description="please refer to /metadata api to see list of available metadata for this properties (range_able_properties)"
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", example="2024-11-06T00:00:00.000000+00:00"),
     *         description="the default value should be current_time with example value representation format."
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", example="2024-11-06T00:00:00.000000+00:00"),
     *         description="the default value should be (current_time + 30 days) with example value representation format."
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", example="1", default="1"),
     *         description="page of the list"
     *     ),
     *     @OA\Response(response="200", description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/RoleListSuccessApiResponse")),
     *     @OA\Response(response="404", description="no rows found",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     *     )
     * 
     * @return Response
     * @throws Exception
     */
    public function actionIndex()
    {
        try {
            $query_params = Yii::$app->request->get();

            // common search queries;
            $page = Yii::$app->request->get(HttpConstants::PAGE);
            $size = Yii::$app->request->get(HttpConstants::SIZE);
            $search = Yii::$app->request->get(HttpConstants::QUERY);
            $sort_by = Yii::$app->request->get(HttpConstants::SORT_BY);
            $sort_order = Yii::$app->request->get(HttpConstants::SORT_ORDER);
            $range_by = Yii::$app->request->get(HttpConstants::RANGE_BY);
            $from = Yii::$app->request->get(HttpConstants::FROM);
            $to = Yii::$app->request->get(HttpConstants::TO);

            // additional filters
            $is_activated = Yii::$app->request->get(RoleConstants::IS_ACTIVATED);

            // pagination
            $pagination = new Pagination($size, $page);

            // dto
            $arg = new ListRoleDTORequest();
            $arg->setPagination($pagination);

            if ($search) {
                $arg->setQ($search);
            }

            if ($sort_by) {
                $arg->setSort_property(strtoupper($sort_by));
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

            if ($range_by) {
                $arg->setRange_property($range_by);
            }

            if (!is_null($is_activated)) {
                $arg->setIs_activated(parent::handleStrBool($is_activated));
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
     *     security={{"ApiKey":{}}},
     *     description="Please refer to CreateRoleParams schema for detailed information on the parameters.",
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
     *     description="Please refer to RoleSuccessApiResponse schema for detailed information on the response body",
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
     *     description="Please refer to UpdateRoleParams schema for detailed information on the parameters.",
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

    /**
     * @OA\Get(
     *     path="/roles/metadata",
     *     tags={"Role"},
     *     security={{"ApiKey":{}}},
     *     description="Please refer to RoleSuccessApiResponse schema for detailed information on the response body",
     *     @OA\Response(response="200", description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/MetadataSuccessApiResponse")),
     *     @OA\Response(response="404", description="no rows found",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     *     )
     * 
     * @return Response
     * @throws Exception
     */
    public function actionMetadata($id)
    {
        try {
            return parent::formatSuccessResponse(200, $this->usecase->metadata(), "OK");
        } catch (Exception $e) {
            return parent::formatErrorResponse($e->getMessage());
        }
    }
}
