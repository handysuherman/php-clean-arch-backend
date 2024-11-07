<?php

namespace app\src\Common\Validations;

interface SchemaValidation
{
    public function validateRequestData(): bool;
}
