<?php

namespace FKS\StringCalculator\Calculators;

use FKS\StringCalculator\Results\DefaultResult;
use PHPUnit\Framework\TestCase;

class DefaultCalculatorTest extends TestCase
{
    /** @var DefaultCalculator */
    protected $calculator;

    /**
     * @param $string
     *
     * @param $validResult
     * @dataProvider provideValidStrings
     */
    public function testCalculate($string, $validResult): void
    {
        $result = $this->calculator->calculate($string);

        $this->assertIsObject($result);
        $this->assertInstanceOf(DefaultResult::class, $result);

        $this->assertObjectHasAttribute('error', $result);
        $this->assertObjectHasAttribute('success', $result);
        $this->assertObjectHasAttribute('result', $result);

        $this->assertTrue($result->isSuccess());
        $this->assertEquals('', $result->getError());
        $this->assertEquals($validResult, $result->getResult());
    }


    /**
     * @param $string
     * @param $result
     * @dataProvider provideValidSimpleStrings
     */
    public function testCalculateSimplePart($string, $result): void
    {
        $this->assertEquals($result, $this->calculator->calculateSimplePart((new CalculationString($string))));
    }

    public function provideValidStrings(): array
    {
        return [
            ['20 /5 + 2', '6'],
            ['5 + (1 + 1)', '7'],
            ['15 *5 *(7+4)', '825'],
        ];
    }

    public function provideValidSimpleStrings(): array
    {
        return [
            ['1 + 1 + 1', '3'],
            ['10 * 10 + 1 *10', '110'],
            ['15 / 5', '3'],
        ];
    }

    protected function setUp(): void
    {
        $this->calculator = new DefaultCalculator();
    }
}
