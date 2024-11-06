
<?php

namespace app\middlewares;

use app\components\RequestContext;
use app\src\Application\Config\Config;
use app\src\Application\Middlewares\AuthMiddleware;
use app\src\Common\Constants\HttpResponseConstants;
use app\src\Common\Exceptions\AuthMiddlewareExceptions\AuthMiddlewareException;
use app\src\Common\Exceptions\TokenExceptions\TokenException;
use app\src\Common\Helpers\HttpError;
use app\src\Common\Helpers\Token;
use app\src\Common\Loggers\Logger;
use Exception;
use ParagonIE\Paseto\Exception\PasetoException;
use Yii;
use yii\base\ActionFilter;
use yii\web\Response;

class ApiAuthMiddleware extends ActionFilter
{
    protected Config $cfg;
    protected Logger $log;
    private AuthMiddleware $middleware;
    private ?array $allowed_roles;
    private RequestContext $request_context;

    public function __construct(Logger $log, ?array $allowed_roles, Config $cfg, $config = [])
    {
        $this->cfg = $cfg;
        $this->log = $log;
        $this->allowed_roles = $allowed_roles;
        $this->middleware = new AuthMiddleware(new Token($this->cfg->getPaseto()));
        $this->request_context = Yii::$app->requestContext;

        parent::__construct($config);
    }

    public function beforeAction($action)
    {
        $context = "ApiAuthMiddleware";
        $auth_token = Yii::$app->request->headers->get(AuthMiddleware::AUTHORIZATION_HEADER_KEY);
        $platform_key = $this->request_context->getContext()->getPlatform_key();
        // $platform_key = Yii::$app->request->headers->get($this->cfg->getApp()->getPlatform()->getPlatform_header_key());

        $allowed_roles = isset($this->allowed_roles) ? $this->allowed_roles : [];

        try {
            $auth_user_claimer = $this->middleware->Validate($auth_token, $platform_key, isset($allowed_roles) && count($allowed_roles) !== 0, $allowed_roles);

            $this->request_context->getContext()->setAuth_user($auth_user_claimer);
        } catch (AuthMiddlewareException $e) {
            $this->log->warning($context, $e->getMessage());
            return $this->handleError($e->getMessage());
        } catch (TokenException $e) {
            $this->log->warning($context, $e->getMessage());
            return $this->handleError($e->getMessage());
        } catch (PasetoException $e) {
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
