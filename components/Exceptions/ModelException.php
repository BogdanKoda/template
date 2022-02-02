<?php

namespace app\components\Exceptions;

use Exception;
use Throwable;

class ModelException extends Exception implements Throwable
{
    private array $errors;

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct("model");
    }
}