<?php

namespace FKS\StringCalculator\Contacts;

use FKS\StringCalculator\Calculators\Result;

interface Calculator
{
    public function calculate(string $string) : Result;
}