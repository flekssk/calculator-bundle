<?php

namespace FKS\StringCalculator\Calculators;

use Symfony\Component\VarDumper\VarDumper;

class CalculationString
{
    protected $string;

    protected $highPriorityOperands = ['*', '/'];

    public function __construct(string $string)
    {
        $this->string = $this->prepareString($string);
    }

    /**
     * Search parentheses
     *
     * @param string $string
     * @return array
     *
     * @todo To most useful need realize object
     */
    public function getSubPartsSeparators(): array
    {
        preg_match_all(
            '/[((?<=\()(.)(?=\)))]/',
            $this->string,
            $subGroups,
            PREG_OFFSET_CAPTURE
        );

        return $subGroups[0];
    }

    public function getPart($start, $length): CalculationString
    {
        return new self(
            substr(
                $this->string,
                $start,
                $length
            )
        );
    }

    public function replacePart($replacement, $start, $length)
    {
        $this->string = substr_replace(
            $this->string,
            $replacement,
            $start,
            $length
        );
    }

    public function getOperators()
    {
        preg_match_all('/[+|-|*]{1,}/', $this->string, $operators, PREG_OFFSET_CAPTURE);

        return $operators[0];
    }

    public function getNumbers()
    {
        preg_match_all('/[0-9]{1,}/', $this->string, $numbers, PREG_OFFSET_CAPTURE);

        return $numbers[0];
    }

    public function getOperatorsByPriority()
    {
        $byPriority = [];
        foreach ($this->getOperators() as $operator) {
            if (in_array($operator[0], $this->highPriorityOperands)) {
                array_unshift($byPriority, $operator);
            } else {
                array_push($byPriority, $operator);
            }
        }

        return $byPriority;
    }

    public function getOperandsByPriority()
    {
        $result  = [
            'numbers'   => [],
            'operators' => $this->getOperatorsByPriority(),
        ];
        $numbers = $this->getNumbers();

        foreach ($result['operators'] as $operator) {
            $operatorPosition = $operator[1];
            $operatorNumbers  = [];
            
            foreach ($numbers as $number) {
                $numberLength = strlen((string)$number[0]);
                if ($number[1] == $operatorPosition - $numberLength) {
                    array_unshift($operatorNumbers, $number);
                }
                if ($number[1] == $operatorPosition + 1) {
                    array_push($operatorNumbers, $number);
                }
            }

            $result['numbers'] = array_merge($result['numbers'], $operatorNumbers);
        }

        return $result;
    }

    protected function prepareString(string $string): string
    {
        $string = str_replace(' ', '', $string);

        return $string;
    }
}