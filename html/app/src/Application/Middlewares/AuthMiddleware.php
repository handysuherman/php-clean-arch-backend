<?php

namespace  app\src\Application\Middlewares;

use app\src\Common\Constants\Exceptions\AuthMiddlewareExceptionMessageConstants;
use app\src\Common\DTOs\ClaimerDTO;
use app\src\Common\Enums\TokenType;
use app\src\Common\Exceptions\AuthMiddlewareExceptions\InvalidAuthorizationFormatException;
use app\src\Common\Exceptions\AuthMiddlewareExceptions\InvalidAuthorizationTypeException;
use app\src\Common\Exceptions\AuthMiddlewareExceptions\InvalidTokenException;
use app\src\Common\Helpers\Token;
use app\src\Common\Loggers\Logger;

class AuthMiddleware
{
    const AUTHORIZATION_HEADER_KEY = "authorization";
    const AUTHORIZATION_TYPE_BEARER = "bearer";

    private Token $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    public function Validate(string $requested_token, string $requested_platform_key, bool $with_role_checks = false, array $allowed_roles = []): ?ClaimerDTO
    {
        if (empty($requested_token)) {
            throw new InvalidTokenException(AuthMiddlewareExceptionMessageConstants::ERR_INVALID_TOKEN);
        }

        $fields = preg_split('/\s+/', $requested_token, -1, PREG_SPLIT_NO_EMPTY);
        if (count($fields) < 2) {
            throw new InvalidAuthorizationFormatException(AuthMiddlewareExceptionMessageConstants::ERR_INVALID_AUTHORIZATION_HEADER_FORMAT);
        }

        $authorization_type = strtolower($fields[0]);
        if ($authorization_type !== self::AUTHORIZATION_TYPE_BEARER) {
            throw new InvalidAuthorizationTypeException(AuthMiddlewareExceptionMessageConstants::ERR_UNSUPPORTED_AUTHORIZATION_TYPE);
        }

        $access_token = $fields[1];

        return $this->token->verify($access_token, TokenType::ACCESS, $requested_platform_key, $with_role_checks, $allowed_roles);
    }
}
