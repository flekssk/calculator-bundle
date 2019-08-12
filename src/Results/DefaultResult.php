<?php

namespace FKS\StringCalculator\Results;

use FKS\StringCalculator\Contacts\Result;

class DefaultResult implements Result
{
    /** @var bool */
    protected $success = true;
    /** @var string */
    protected $error = '';
    /** @var int */
    protected $result = 0;

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return mixed
     */
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    public function toArray()
    {
        return [
            'success' => $this->isSuccess(),
            'result'  => $this->getResult(),
            'error'   => $this->getError(),
        ];
    }
}