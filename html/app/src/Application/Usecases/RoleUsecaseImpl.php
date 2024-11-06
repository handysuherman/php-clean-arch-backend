<?php

namespace app\src\Application\Usecases;

use app\src\Application\Config\Config;
use app\src\Application\Contexts\RequestContext;
use app\src\Common\DTOs\Request\Role\CreateRoleDTORequest;
use app\src\Common\DTOs\Response\RoleDTOResponse;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Text;
use app\src\Common\Helpers\Time;
use app\src\Common\Loggers\Logger;
use app\src\Domain\Entities\RoleEntity;
use app\src\Domain\Factories\RoleFactory;
use app\src\Infrastructure\Repository\MySQL\RoleRepository;
use Ulid\Exception\InvalidUlidStringException;
use Ulid\Ulid;

class RoleUsecaseImpl implements RoleUsecase
{
    private string $scope = "RoleUsecase";
    protected RoleRepository $repository;
    protected Config $config;
    protected Logger $log;

    public function __construct(RoleRepository $repository, Config $config, Logger $log)
    {
        $this->repository = $repository;
        $this->config = $config;
        $this->log = $log;
    }

    public function save(RequestContext $ctx, CreateRoleDTORequest $arg): string
    {
        $uid = Identifier::newULID();

        $created_at = Time::atomicMicroFormat(Time::now());
        $created_by = $ctx->getAuth_user() ? $ctx->getAuth_user()->getUser_id() : 'system';

        $entity = new RoleEntity();
        $entity->setUid($uid);
        $entity->setRole_name($arg->getRole_name());
        $entity->setDescription($arg->getDescription());
        $entity->setRole_name_slug(Text::toSlugify($arg->getRole_name(), true));
        $entity->setCreated_at($created_at);
        $entity->setCreated_by($created_by);

        $this->repository->save($entity);

        return $uid;
    }

    public function getByUid(RequestContext $ctx, string $uid): RoleDTOResponse
    {
        try {
            $context = $this->scope . "getByUid";

            $parsed_uid = Ulid::fromString($uid);

            $response = $this->repository->findByUid($parsed_uid->__toString());

            return RoleFactory::toDTOResponse($response);
        } catch (InvalidUlidStringException $e) {
            $this->log->warning($context, $e->getMessage());

            throw $e;
        }
    }
}
