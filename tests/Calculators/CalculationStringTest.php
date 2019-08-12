<?php

namespace FKS\StringCalculator\Calculators;

use PHPUnit\Framework\TestCase;

class CalculationStringTest extends TestCase
{

    public function testGetOperatorsByPriority()
    {
        $result = new CalculationString('15 + 22 * 5');

        $operators = $result->getOperatorsByPriority();
        $this->assertArrayHasKey(0, $operators);
        $this->assertEquals('*', $operators[0][0]);
        $this->assertEquals(5, $operators[0][1]);

        $operandsByPriority = $result->getOperandsByPriority();
        $this->assertArrayHasKey('numbers', $operandsByPriority);
        $this->assertArrayHasKey('operators', $operandsByPriority);
        $this->assertEquals(22, $operandsByPriority['numbers'][0][0]);
        $this->assertEquals(3, $operandsByPriority['numbers'][0][1]);
        $this->assertEquals('*', $operandsByPriority['operators'][0][0]);
        $this->assertEquals(5, $operandsByPriority['operators'][0][1]);
    }
}
