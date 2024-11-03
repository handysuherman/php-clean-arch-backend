<?php

namespace app\src\Common\Helpers;
// sign
use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\Protocol\Version4;
use ParagonIE\Paseto\Keys\Base\{AsymmetricPublicKey, AsymmetricSecretKey};
use ParagonIE\Paseto\Purpose;

// parser
use ParagonIE\Paseto\Rules\{ValidAt};
use ParagonIE\Paseto\ProtocolCollection;
use ParagonIE\Paseto\Parser;
use ParagonIE\Paseto\Exception\PasetoException;

use app\src\Application\Config\PasetoKeyManager;
use app\src\Common\DTOs\ClaimerDTO;
use app\src\Common\Enums\TokenType;
use app\src\Common\Exceptions\TokenExceptions\InsufficientTokenPermissionException;
use app\src\Common\Exceptions\TokenExceptions\TokenExpiredException;
use app\src\Common\Exceptions\TokenExceptions\TokenPlatformNotMatchException;
use app\src\Common\Exceptions\TokenExceptions\TokenTypeNotMatchException;
use app\src\Domain\Factories\ClaimerDTOFactory;
use app\src\Domain\Factories\ClaimerRoleDTOFactory;
use Exception;
use ParagonIE\Paseto\Exception\RuleViolation;

class Token
{
    private PasetoKeyManager $key_manager;
    private AsymmetricSecretKey $private_key;
    private AsymmetricPublicKey $public_key;

    public function __construct(PasetoKeyManager $key_manager)
    {
        $this->key_manager = $key_manager;
        $this->private_key = AsymmetricSecretKey::importPem($this->key_manager->getPrivateKey());
        $this->public_key = AsymmetricPublicKey::importPem($this->key_manager->getPublicKey());
    }

    public function create(ClaimerDTO $payload): string
    {
        $token = Builder::getPublic($this->private_key, new Version4);

        $token = (new Builder())
            ->setKey($this->private_key)
            ->setVersion(new Version4)
            ->setIssuedAt()
            ->setNotBefore()
            ->setPurpose(Purpose::public())
            ->setExpiration(Time::stringToDateTime($payload->getExpires_at()))
            ->setClaims(ClaimerDTOFactory::toArray($payload));

        return $token->toString();
    }

    public function verify(string $token, TokenType $expected_type, string $expected_platform_key, bool $with_roles_checks = false, array $allowed_roles = []): ?ClaimerDTO
    {

        $parser = Parser::getPublic($this->public_key, ProtocolCollection::v4())
            ->addRule(new ValidAt());

        $parser = (new Parser())
            ->setKey($this->public_key)
            ->addRule(new ValidAt())
            ->setPurpose(Purpose::public())
            ->setAllowedVersions(ProtocolCollection::v4());

        try {
            $token = $parser->parse($token);
            $payloadArray = $token->getClaims();

            $dto = ClaimerDTOFactory::fromArray($payloadArray);

            if ($dto->getType() !== $expected_type) {
                throw new TokenTypeNotMatchException("invalid type access");
            }

            if ($dto->getPlatform_key() !== $expected_platform_key) {
                throw new TokenPlatformNotMatchException("invalid platform method");
            }

            if ($with_roles_checks) {
                $this->withRolesCheck($dto, $allowed_roles);
            }

            return $dto;
        } catch (RuleViolation $e) {
            if (strpos($e->getMessage(), "This token has expired") !== false) {
                throw new TokenExpiredException($e->getMessage());
            };

            throw $e;
        } catch (PasetoException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function withRolesCheck(ClaimerDTO $dto, array $allowed_roles)
    {
        $flipped_allowed_roles = array_flip($allowed_roles);

        $is_allowed = false;

        foreach ($dto->getRoles() as $claimer_role_value) {
            $claimer_role = ClaimerRoleDTOFactory::fromArray($claimer_role_value);

            if (isset($flipped_allowed_roles[$claimer_role->getRole_name()])) {
                if ($claimer_role->getIs_activated() && !$claimer_role->getIs_blocked()) {
                    $is_allowed = true;
                    break;
                }
            }
        }

        if (!$is_allowed) {
            throw new InsufficientTokenPermissionException("request not allowed due to insufficient permissions");
        }
    }
}
