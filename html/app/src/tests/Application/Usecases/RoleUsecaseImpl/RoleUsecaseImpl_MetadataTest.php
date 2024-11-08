<?php

use app\src\Common\Constants\HttpConstants;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Domain\Builders\RoleQueryBuilder;
use app\src\Domain\Factories\RoleFactory;
use app\src\tests\Application\Usecases\RoleUsecaseImpl\RoleUsecaseImpl_Test;
use Ulid\Exception\InvalidUlidStringException;

// TODO: more edge cases;
class RoleUsecaseImpl_MetadataTest extends RoleUsecaseImpl_Test
{
    protected string $error_message = "";

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testMetadata_OK()
    {
        $response = $this->usecase->metadata();
        $qb = new RoleQueryBuilder();

        $this->assertTrue(isset($response[HttpConstants::OPERATIONS]));
        $this->assertTrue(isset($response[HttpConstants::OPERATIONS][HttpConstants::DATA_LIST]));

        $this->assertTrue(isset($response[HttpConstants::OPERATIONS][HttpConstants::DATA_LIST][HttpConstants::SORT_ABLE_PROPERTIES]));

        $this->assertEquals($response[HttpConstants::OPERATIONS][HttpConstants::DATA_LIST][HttpConstants::SORT_ABLE_PROPERTIES], $qb->getSortable_columns());
        $this->assertEquals($response[HttpConstants::OPERATIONS][HttpConstants::DATA_LIST][HttpConstants::RANGE_ABLE_PROPERTIES], $qb->getRange_betweenable_columns());
    }
}
