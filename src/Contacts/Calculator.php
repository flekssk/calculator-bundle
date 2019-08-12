<?php

namespace FKS\StringCalculator\Contacts;

interface Calculator
{
    public function calculate(string $string): Result;
}