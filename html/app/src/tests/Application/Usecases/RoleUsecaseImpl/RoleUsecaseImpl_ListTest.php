<?php

use app\src\Application\Contexts\RequestContext;
use app\src\Common\DTOs\Request\Role\ListRoleDTORequest;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Pagination;
use app\src\Domain\Entities\QueryParameterEntity;
use app\src\Domain\Factories\QueryParameterFactory;
use app\src\Domain\Factories\RoleFactory;
use app\src\Domain\Params\RoleQueryParams;
use app\src\Infrastructure\Constants\RoleConstants;
use app\src\tests\Application\Usecases\RoleUsecaseImpl\RoleUsecaseImpl_Test;

class RoleUsecaseImpl_ListTest extends RoleUsecaseImpl_Test
{
    protected string $error_message = "";

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testList_Case_OK()
    {
        $list = [];
        $uid = Identifier::newULID();
        $ctx = $this->createRandomContext($uid);
        $arg =  $this->createRandom();
        $arg->setIs_activated(true);

        $search_arg = new RoleQueryParams();
        $search_arg->setSearch_text($arg->getQ());
        $search_arg->setSort_order($arg->getSort_order());
        $search_arg->setPagination($arg->getPagination());

        $entity = RoleFactory::createRandomEntity();

        $list[] = RoleFactory::toKeyValArray($entity);

        $this->repository->expects($this->once())
            ->method("list")
            ->with($this->callback(function (RoleQueryParams $repo_arg) use ($arg, $ctx) {
                return $this->customParamsMatcher($arg, $repo_arg, $ctx);
            }), true)
            ->willReturn($list);

        $this->repository->expects($this->once())
            ->method("count")
            ->with($this->callback(function (RoleQueryParams $repo_arg) use ($arg, $ctx) {
                return $this->customParamsMatcher($arg, $repo_arg, $ctx);
            }), true)
            ->willReturn(1);

        $this->usecase->list($ctx, $arg);
    }

    private function customParamsMatcher(ListRoleDTORequest $actual_arg, RoleQueryParams $arg, RequestContext $ctx): bool
    {
        $this->error_message = "";
        $additional_filters = [];


        if ($actual_arg->getPagination() != $arg->getPagination()) {
            $this->error_message .= sprintf("expected pagination %s, got %s\n", $actual_arg->getPagination(), $arg->getPagination());
        };

        if (!is_null($actual_arg->getIs_activated())) {
            $is_activated_filter = new QueryParameterEntity();

            $is_activated_filter->setColumn(RoleConstants::IS_ACTIVATED);
            $is_activated_filter->setValue($actual_arg->getIs_activated());
            $is_activated_filter->setSql_data_type(\PDO::PARAM_BOOL);
            $is_activated_filter->setTruthy("=");

            $additional_filters[] = QueryParameterFactory::toKeyValArray($is_activated_filter);
        }

        if ($arg->getQuery_params() !== $additional_filters) {
            $this->error_message .= sprintf("expected query_params %s, got %s\n", json_encode($additional_filters), json_encode($arg->getQuery_params()));
        }

        if ($actual_arg->getSort_property()) {
            if ($actual_arg->getSort_property() !== $arg->getSort_property()) {
                $this->error_message .= sprintf("expected sort_property %s, got %s\n", $actual_arg->getSort_property(), $arg->getSort_property());
            }
        }

        if ($actual_arg->getRange_property()) {
            if ($actual_arg->getRange_property() !== $arg->getRange_property()) {
                $this->error_message .= sprintf("expected range_properties %s, got %s\n", $actual_arg->getRange_property(), $arg->getRange_property());
            }
        }

        if (!empty($this->error_message)) {
            $this->fail($this->error_message);
        }

        return empty($this->error_message);
    }

    private function createRandom(): ListRoleDTORequest
    {
        $pagination = new Pagination();

        $arg = new ListRoleDTORequest();
        $arg->setPagination($pagination);

        return $arg;
    }
}
