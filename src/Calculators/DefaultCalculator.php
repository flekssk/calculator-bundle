<?php

namespace FKS\StringCalculator\Calculators;

use FKS\StringCalculator\Contacts\Calculator;
use FKS\StringCalculator\Contacts\Result;
use FKS\StringCalculator\Exceptions\CalculatorException;
use FKS\StringCalculator\Exceptions\WrongCharacterSequence;
use FKS\StringCalculator\Results\DefaultResult;
use Psr\Container\ContainerInterface;

class DefaultCalculator implements Calculator
{
    /**
     * @param string $string
     * @return Result
     *
     * @todo Pass message to exceptions
     */
    public function calculate(string $string): Result
    {
        $result = new DefaultResult();
        $string = new CalculationString($string);

        try {
            $subPartsSeparator = $string->getSubPartsSeparators();
            if (count($subPartsSeparator) > 0) {
                if ($subPartsSeparator[0] == ")" || count($subPartsSeparator) % 2 > 0) {
                    throw new WrongCharacterSequence();
                }

                for ($i = 0; $i < count($subPartsSeparator);) {
                    $startPart  = $subPartsSeparator[$i];
                    $closedPart = $subPartsSeparator[$i + 1];

                    $string->replacePart(
                        $this->calculateSimplePart(
                            $string->getPart(
                                $startPart[1] + 1,
                                $closedPart[1] - $startPart[1] - 1
                            )
                        ),
                        $startPart[1],
                        $closedPart[1] - $startPart[1] + 1
                    );

                    $i += 2;
                }
            }

            $result->setResult($this->calculateSimplePart($string));

        } catch (CalculatorException $exception) {
            $result->setSuccess(false);
        }

        return $result;
    }

    public function calculateSimplePart(CalculationString $string)
    {
        $operands = $string->getOperandsByPriority();
        $result   = (int)array_shift($operands['numbers'])[0];

        foreach ($operands['operators'] as $operator) {
            switch ($operator[0]) {
                case '+':
                    $result = $result + (int)array_shift($operands['numbers'])[0];
                    break;
                case '-':
                    $result = $result - (int)array_shift($operands['numbers'])[0];
                    break;
                case '*':
                    $result = $result * (int)array_shift($operands['numbers'])[0];
                    break;
                case '/':
                    $result = $result / (int)array_shift($operands['numbers'])[0];
                    break;
            }
        }

        return $result;
    }
}