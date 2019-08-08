<?php

namespace FKS\stringCalculator\Contacts;

use FKS\stringCalculator\Calculators\Result;

interface Calculator
{
    public function calculate(string $string) : Result;
}