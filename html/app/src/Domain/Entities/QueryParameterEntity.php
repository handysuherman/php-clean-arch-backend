<?php

namespace app\src\Domain\Entities;

class QueryParameterEntity
{
	private ?string $column;
	private mixed $value = null;
	private mixed $sql_data_type = null;
	private ?string $truthy = null;
	private ?string $truthy_operator = null;

	public function getColumn(): ?string
	{
		return $this->column;
	}

	public function setColumn(?string $value)
	{
		$this->column = $value;
	}

	public function getValue(): mixed
	{
		return $this->value;
	}

	public function setValue(mixed $value)
	{
		$this->value = $value;
	}

	public function getSql_data_type(): mixed
	{
		return $this->sql_data_type;
	}

	public function setSql_data_type(mixed $value)
	{
		$this->sql_data_type = $value;
	}

	public function getTruthy(): ?string
	{
		return $this->truthy;
	}

	public function setTruthy(?string $value)
	{
		$this->truthy = $value;
	}

	public function getTruthy_operator(): ?string
	{
		return $this->truthy_operator;
	}

	public function setTruthy_operator(?string $value)
	{
		$this->truthy_operator = $value;
	}
}
