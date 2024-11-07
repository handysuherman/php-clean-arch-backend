<?php

namespace app\controllers\api\v1;

use app\components\RequestContext;
use app\middlewares\ApiMiddleware;
use app\src\Application\Config\Config;
use app\src\Common\Constants\HttpResponseConstants;
use app\src\Common\Helpers\Http;
use app\src\Common\Helpers\HttpError;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Loggers\Logger;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * @OA\Info(title="Bridge API", version="1.0.0")
 * @OA\Server(url="http://your-api-url.com/api/v1")
 * @OA\PathItem(path="/api/v1")
 * @OA\SecurityRequirement(
 *     securitySchemes={"ApiKey", "Token"}
 * )
 */
class ApiController extends Controller
{
    protected Config $cfg;
    protected Logger $log;
    protected RequestContext $request_context;

    public function __construct($id, $module, Logger $log, Config $cfg, $config = [])
    {
        $this->cfg = $cfg;
        $this->log = $log;
        $this->request_context = Yii::$app->requestContext;

        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors["contentNegotiator"]["formats"]["application/json"] = Response::FORMAT_JSON;

        $behaviors['apiMiddleware'] = function () {
            return new ApiMiddleware($this->log, $this->cfg);
        };

        return $behaviors;
    }

    public function formatUidArr(string $uid): array
    {
        return [
            HttpResponseConstants::UID => Identifier::encrypt($uid)
        ];
    }

    public function formatSuccessResponse($status, $data = null, $message = null)
    {
        $response = Http::SuccessResponse($status, $data, $message);

        Yii::$app->response->statusCode = $status;
        return $response;
    }

    public function formatErrorResponse($error_message)
    {
        $error_response = HttpError::ParseError($error_message, true);

        Yii::$app->response->format = Response::FORMAT_JSON;

        Yii::$app->response->statusCode = $error_response[HttpResponseConstants::STATUS];

        return $error_response;
    }
}
