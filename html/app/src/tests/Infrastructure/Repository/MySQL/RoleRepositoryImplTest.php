<?php

use app\src\Application\Config\Config;
use app\src\Common\Databases\MySQL;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Pagination;
use app\src\Common\Loggers\Logger;
use app\src\Domain\Entities\QueryParameterEntity;
use app\src\Domain\Entities\RoleEntity;
use app\src\Domain\Factories\QueryParameterFactory;
use app\src\Domain\Factories\RoleFactory;
use app\src\Domain\Params\RoleQueryParams;
use app\src\Infrastructure\Constants\RoleConstants;
use app\src\Infrastructure\Repository\MySQL\RoleRepository;
use app\src\Infrastructure\Repository\MySQL\RoleRepositoryImpl;
use app\src\tests\BaseTest;
use Dotenv\Dotenv;

class RoleRepositoryImplTest extends BaseTest
{
    private ?RoleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        parent::setMySQL();

        $this->repository = new RoleRepositoryImpl($this->config, $this->log, $this->mysql);
    }

    public function testSave()
    {
        $this->createRandom();
        $this->assertTrue(true);
    }

    public function testFindByUid()
    {
        $arg = $this->createRandom();

        $response = $this->repository->findByUid(Identifier::decrypt($arg->getUid(), $this->config->getKeys()->getApp_identifier_key()));

        $this->assertNotEmpty($response);
        $this->assertEquals(Identifier::decrypt($arg->getUid(), $this->config->getKeys()->getApp_identifier_key()), Identifier::decrypt($response->getUid(), $this->config->getKeys()->getApp_identifier_key()));
        $this->assertEquals($arg->getRole_name(), $response->getRole_name());
        $this->assertEquals($arg->getRole_name_slug(), $response->getRole_name_slug());
        $this->assertEquals($arg->getCreated_at(), $response->getCreated_at());
        $this->assertEquals($arg->getCreated_by(), $response->getCreated_by());
        $this->assertEquals($arg->getIs_activated(), $response->getIs_activated());
        $this->assertEquals($arg->getIs_activated_updated_at(), $response->getIs_activated_updated_at());
        $this->assertEquals($arg->getIs_activated_updated_by(), $response->getIs_activated_updated_by());
    }

    public function testUpdateRoleNameOnly()
    {
        $arg = $this->createRandom();

        $update_filters = [];

        $updated_role_name = new QueryParameterEntity();
        $updated_role_name->setColumn(RoleConstants::ROLE_NAME);
        $updated_role_name->setValue(Generation::randomString(100));
        $updated_role_name->setSql_data_type(\PDO::PARAM_STR);

        $update_filters[] = QueryParameterFactory::toKeyValArray($updated_role_name);

        $this->repository->update(Identifier::decrypt($arg->getUid(), $this->config->getKeys()->getApp_identifier_key()), $update_filters);

        $response = $this->repository->findByUid(Identifier::decrypt($arg->getUid(), $this->config->getKeys()->getApp_identifier_key()));

        $this->assertNotEmpty($response);

        $this->assertNotEquals($arg->getRole_name(), $response->getRole_name());
        $this->assertEquals($updated_role_name->getValue(), $response->getRole_name());

        $this->assertEquals(Identifier::decrypt($arg->getUid(), $this->config->getKeys()->getApp_identifier_key()), Identifier::decrypt($response->getUid(), $this->config->getKeys()->getApp_identifier_key()));
        $this->assertEquals($arg->getRole_name_slug(), $response->getRole_name_slug());
        $this->assertEquals($arg->getCreated_at(), $response->getCreated_at());
        $this->assertEquals($arg->getCreated_by(), $response->getCreated_by());
        $this->assertEquals($arg->getIs_activated(), $response->getIs_activated());
        $this->assertEquals($arg->getIs_activated_updated_at(), $response->getIs_activated_updated_at());
        $this->assertEquals($arg->getIs_activated_updated_by(), $response->getIs_activated_updated_by());
    }

    public function testUpdateRoleNameOnlyWithVariousFilters()
    {
        $arg = $this->createRandom();
        $update_filters = [];

        $updated_role_name = new QueryParameterEntity();
        $updated_role_name->setColumn(RoleConstants::ROLE_NAME);
        $updated_role_name->setValue(Generation::randomString(100));
        $updated_role_name->setSql_data_type(\PDO::PARAM_STR);

        $update_filters[] = QueryParameterFactory::toKeyValArray($updated_role_name);

        $filters = [];

        $role_name_slug = new QueryParameterEntity();
        $role_name_slug->setColumn(RoleConstants::ROLE_NAME_SLUG);
        $role_name_slug->setValue($arg->getRole_name_slug());
        $role_name_slug->setSql_data_type(\PDO::PARAM_STR);
        $role_name_slug->setTruthy_operator("=");

        $filters[] = QueryParameterFactory::toKeyValArray($role_name_slug);

        $this->repository->update(Identifier::decrypt($arg->getUid(), $this->config->getKeys()->getApp_identifier_key()), $update_filters, $filters);

        $response = $this->repository->findByUid(Identifier::decrypt($arg->getUid(), $this->config->getKeys()->getApp_identifier_key()));

        $this->assertNotEmpty($response);

        $this->assertNotEquals($arg->getRole_name(), $response->getRole_name());
        $this->assertEquals($updated_role_name->getValue(), $response->getRole_name());

        $this->assertEquals(Identifier::decrypt($arg->getUid(), $this->config->getKeys()->getApp_identifier_key()), Identifier::decrypt($response->getUid(), $this->config->getKeys()->getApp_identifier_key()));
        $this->assertEquals($arg->getRole_name_slug(), $response->getRole_name_slug());
        $this->assertEquals($arg->getCreated_at(), $response->getCreated_at());
        $this->assertEquals($arg->getCreated_by(), $response->getCreated_by());
        $this->assertEquals($arg->getIs_activated(), $response->getIs_activated());
        $this->assertEquals($arg->getIs_activated_updated_at(), $response->getIs_activated_updated_at());
        $this->assertEquals($arg->getIs_activated_updated_by(), $response->getIs_activated_updated_by());
    }

    public function testList()
    {
        $arg = $this->createRandom();
        $list_params = new RoleQueryParams();
        $list_params->setPagination(new Pagination());
        $list_params->setSearch_text($arg->getRole_name());

        $is_activated_flag = new QueryParameterEntity();
        $is_activated_flag->setColumn(RoleConstants::IS_ACTIVATED);
        $is_activated_flag->setValue($arg->getIs_activated());
        $is_activated_flag->setSql_data_type(\PDO::PARAM_BOOL);
        $is_activated_flag->setTruthy("AND");
        $is_activated_flag->setTruthy_operator("=");

        $filters = [];

        $filters[] = QueryParameterFactory::toKeyValArray($is_activated_flag);

        $list_params->setQuery_params($filters);

        $response = $this->repository->list($list_params);
        $this->assertNotEmpty($response);
    }

    public function testCount()
    {
        $arg = $this->createRandom();
        $list_params = new RoleQueryParams();
        $list_params->setPagination(new Pagination());
        $list_params->setSearch_text($arg->getRole_name());

        $is_activated_flag = new QueryParameterEntity();
        $is_activated_flag->setColumn(RoleConstants::IS_ACTIVATED);
        $is_activated_flag->setValue($arg->getIs_activated());
        $is_activated_flag->setSql_data_type(\PDO::PARAM_BOOL);
        $is_activated_flag->setTruthy("AND");
        $is_activated_flag->setTruthy_operator("=");

        $filters = [];

        $filters[] = QueryParameterFactory::toKeyValArray($is_activated_flag);

        $list_params->setQuery_params($filters);

        $response = $this->repository->count($list_params);
        $this->assertNotEmpty($response);
    }

    private function createRandom(): RoleEntity
    {
        $arg = RoleFactory::createRandomEntity();

        $this->repository->save($arg);

        $response = $this->repository->findByUid($arg->getUid());

        return $response;
    }


    protected function tearDown(): void
    {
        $this->repository = null;

        parent::tearDown();
    }
}
