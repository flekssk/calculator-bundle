<?php

namespace FKS\StringCalculator\Calculators;

use FKS\StringCalculator\Contacts\Calculator;
use FKS\StringCalculator\Contacts\Result;
use FKS\StringCalculator\Exceptions\CalculatorException;
use FKS\StringCalculator\Exceptions\WrongCharacterSequence;
use FKS\StringCalculator\Results\DefaultResult;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

class DefaultCalculator implements Calculator
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function calculate(string $string): Result
    {
        $result = new DefaultResult();

        try {
            $subPartsSeparator = $this->searchSubPartsSeparators($string);

            if (count($subPartsSeparator[0]) > 0) {
                if ($subPartsSeparator[0][0] == ")" || count($subPartsSeparator) % 2 > 0) {
                    throw new WrongCharacterSequence();
                }

                for ($i = 0; $i < $subPartsSeparator; ($i + 2)) {
                    $startPart  = $subPartsSeparator[$i];
                    $closedPart = $subPartsSeparator[$i + 1];
                    $string     = substr_replace(
                        $string,
                        $this->calculateSimplePart(
                            substr(
                                $string,
                                $startPart[1],
                                $closedPart[1] - $startPart[1]
                            )
                        ),
                        $startPart[1],
                        $closedPart[1] - $startPart[1]
                    );
                }
            }

            $result->setResult($this->calculateSimplePart($string));

        } catch (CalculatorException $exception) {
            $result->setSuccess(false);
        }

        return $result;
    }

    /**
     * Search parentheses
     *
     * @param string $string
     * @return array
     *
     * @todo To most useful need realize object
     */
    public function searchSubPartsSeparators(string $string): array
    {
        preg_match_all(
            '/[((?<=\()(.)(?=\)))]/',
            $string,
            $subGroups,
            PREG_OFFSET_CAPTURE
        );

        return $subGroups;
    }

    public function calculateSimplePart(string $string)
    {
        preg_match_all('/[0-9]{1,}/', $string, $numbers);
        preg_match_all('/[+|-]{1,}/', $string, $operators);

        $numbers = array_shift($numbers);
        $result  = (int)array_shift($numbers);

        foreach ($operators[0] as $operator) {
            if ($operator == '+') {
                $result = $result + (int)array_shift($numbers);
            } else {
                $result = $result - (int)array_shift($numbers);
            }
        }

        return $result;
    }
}