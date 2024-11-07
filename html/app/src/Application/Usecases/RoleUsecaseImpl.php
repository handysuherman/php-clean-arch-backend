<?php

namespace app\src\Application\Usecases;

use app\src\Application\Config\Config;
use app\src\Application\Contexts\RequestContext;
use app\src\Common\DTOs\Request\Role\CreateRoleDTORequest;
use app\src\Common\DTOs\Request\Role\ListRoleDTORequest;
use app\src\Common\DTOs\Request\Role\RoleDTORequest;
use app\src\Common\DTOs\Request\Role\UpdateRoleDTORequest;
use app\src\Common\DTOs\Response\RoleDTOResponse;
use app\src\Common\Exceptions\ValidationExceptions\RequiredPropertyException;
use app\src\Common\Exceptions\ValidationExceptions\ValidationException;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Pagination;
use app\src\Common\Helpers\Text;
use app\src\Common\Helpers\Time;
use app\src\Common\Loggers\Logger;
use app\src\Domain\Entities\QueryParameterEntity;
use app\src\Domain\Entities\RoleEntity;
use app\src\Domain\Factories\QueryParameterFactory;
use app\src\Domain\Factories\RoleFactory;
use app\src\Domain\Params\RoleQueryParams;
use app\src\Infrastructure\Constants\RoleConstants;
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
        try {
            $arg->validateRequestData();

            $context = $this->scope . "save";
            $uid = Identifier::newULID();

            $created_at = Time::atomicMicroFormat(Time::now());
            $created_by = $ctx->getAuth_user() ? $ctx->getAuth_user()->getUser_id() : 'system';

            $entity = new RoleEntity();
            $entity->setUid($uid);
            $entity->setRole_name($arg->getRole_name());
            $entity->setRole_name_slug(Text::toSlugify($arg->getRole_name(), true));
            $entity->setDescription($arg->getDescription());
            $entity->setCreated_at($created_at);
            $entity->setCreated_by($created_by);

            $this->repository->save($entity);

            return $uid;
        } catch (ValidationException $e) {
            $this->log->warning($context, sprintf("validation exception thrown: %s", $e->getMessage()));

            throw $e;
        }
    }

    public function update(RequestContext $ctx, UpdateRoleDTORequest $arg): string
    {
        try {
            $context = $this->scope . "update";
            $current_time = Time::atomicMicroFormat(Time::now());

            $arg->validateRequestData();

            if ($arg->getRole_name()) {
                $arg->setRole_name_slug(Text::toSlugify($arg->getRole_name(), true));
            }

            if (!is_null($arg->getIs_activated())) {
                $arg->setIs_activated_updated_at($current_time);
                $arg->setIs_activated_updated_by($ctx->getAuth_user() ? $ctx->getAuth_user()->getUser_id() : 'system');
            }

            $this->repository->update($arg->getUid(), RoleFactory::createUpdateArray($arg));

            return $arg->getUid();
        } catch (InvalidUlidStringException $e) {
            $this->log->warning($context, sprintf("invalid uid: %s", $e->getMessage()));

            throw $e;
        } catch (ValidationException $e) {
            $this->log->warning($context, sprintf("validation exception thrown: %s", $e->getMessage()));

            throw $e;
        }
    }

    public function getByUid(RequestContext $ctx, string $uid): RoleDTOResponse
    {
        try {
            $context = $this->scope . "getByUid";

            $parsed_uid = Ulid::fromString($uid);

            $response = $this->repository->findByUid($parsed_uid->__toString());

            return RoleFactory::toDTOResponse($response);
        } catch (InvalidUlidStringException $e) {
            $this->log->warning($context, sprintf("invalid uid: %s", $e->getMessage()));

            throw $e;
        }
    }

    public function list(RequestContext $ctx, ListRoleDTORequest $arg, Pagination $pagination): array
    {
        $context = $this->scope . "list";

        $additional_filters = [];

        $search_arg = new RoleQueryParams();
        $search_arg->setSearch_text($arg->getQ());
        $search_arg->setSort_order($arg->getSort_order());
        $search_arg->setPagination($pagination);

        if ($arg->getFrom()) {
            $search_arg->setFrom($arg->getFrom());
            $search_arg->setTo($arg->getTo());
        }

        if (!is_null($arg->getIs_activated())) {
            var_dump("inside usecase");
            var_dump($arg->getIs_activated());
            $is_activated_filter = new QueryParameterEntity();

            $is_activated_filter->setColumn(RoleConstants::IS_ACTIVATED);
            $is_activated_filter->setValue($arg->getIs_activated());
            $is_activated_filter->setSql_data_type(\PDO::PARAM_BOOL);
            $is_activated_filter->setTruthy("=");

            var_dump("inside filter");
            var_dump($is_activated_filter->getValue());

            $additional_filters[] = QueryParameterFactory::toKeyValArray($is_activated_filter);
        }

        if ($arg->getSort_property()) {
            $search_arg->setSort_property($arg->getSort_property());
        }

        if ($arg->getRange_property()) {
            $search_arg->setRange_property($arg->getRange_property());
        }

        $search_arg->setQuery_params($additional_filters);

        $list_response = $this->repository->list($search_arg, true);
        $total_count_search_text_response = $this->repository->count($search_arg, true);
        $pagination->setTotal_count($total_count_search_text_response);

        return $pagination->toPaginationResponse($list_response);
    }
}
