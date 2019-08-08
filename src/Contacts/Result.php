<?php

namespace FKS\stringCalculator\Contacts;

interface Result
{
    /**
     * Return true if result succeed or false if not
     *
     * @return bool
     */
    public function isSuccess() : bool;

    /**
     * Return the calculation result
     *
     * @return int
     */
    public function getResult() : int;

    /**
     * Return the error string
     *
     * @return string
     */
    public function getErrors() : string;
}