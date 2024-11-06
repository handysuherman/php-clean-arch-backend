<?php

namespace app\middlewares;

use app\components\RequestContext;
use app\src\Application\Config\Config;
use app\src\Application\Middlewares\PlatformPermissionMiddleware;
use app\src\Common\Constants\HttpResponseConstants;
use app\src\Common\Exceptions\PlatformPermissionMiddlewareExceptions\PlatformPermissionMiddlewareException;
use app\src\Common\Helpers\HttpError;
use app\src\Common\Loggers\Logger;
use Exception;
use Yii;
use yii\base\ActionFilter;
use yii\web\Response;

class ApiMiddleware extends ActionFilter
{
    protected Config $cfg;
    protected Logger $log;
    private PlatformPermissionMiddleware $middleware;
    private RequestContext $request_context;

    public function __construct(Logger $log, Config $cfg, $config = [])
    {
        $this->cfg = $cfg;
        $this->log = $log;;
        $this->middleware = new PlatformPermissionMiddleware($this->cfg);
        $this->request_context = Yii::$app->requestContext;

        parent::__construct($config);
    }


    public function beforeAction($action)
    {
        $context = "ApiMiddleware";
        $platform_key = Yii::$app->request->headers->get($this->cfg->getApp()->getPlatform()->getPlatform_header_key());

        try {
            $this->middleware->Validate($platform_key);

            $this->request_context->getContext()->setPlatform_key($platform_key);
            $this->request_context->getContext()->setUser_ip(Yii::$app->request->getUserIP());
            $this->request_context->getContext()->setUser_agent(Yii::$app->request->getUserAgent());

            return parent::beforeAction($action);
        } catch (PlatformPermissionMiddlewareException $e) {
            $this->log->warning($context, $e->getMessage());
            return $this->handleError($e->getMessage());
        } catch (Exception $e) {
            $this->log->warning($context, $e->getMessage());
            return $this->handleError($e->getMessage());
        }
    }


    private function handleError(string $error_message): bool
    {
        $error_response = HttpError::ParseError($error_message, true);

        Yii::$app->response->format = Response::FORMAT_JSON;

        Yii::$app->response->statusCode = $error_response[HttpResponseConstants::STATUS];

        Yii::$app->response->data = $error_response;

        return false;
    }
}
