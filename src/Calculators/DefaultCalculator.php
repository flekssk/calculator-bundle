<?php

namespace FKS\stringCalculator\Calculators;

use Exception;
use FKS\stringCalculator\Contacts\Calculator;
use FKS\stringCalculator\Exceptions\WrongCharacterSequence;

class DefaultCalculator implements Calculator
{
    public function calculate(string $string): Result
    {
        $result = new Result();

        try {
            $subPartsSeparator = $this->searchSubPartsSeparators($string);

            if (count($subPartsSeparator) > 0) {
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

        } catch (Exception $exception) {
            $result->setSuccess(false);
        }

        return $result;
    }

    public function searchSubPartsSeparators(string $string): array
    {
        return preg_match_all(
            '/[((?<=\()(.)(?=\)))]/',
            $string,
            $subGroups,
            PREG_OFFSET_CAPTURE
        );
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