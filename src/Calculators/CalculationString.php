<?php

namespace FKS\StringCalculator\Calculators;

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
        preg_match_all('/[+|-|*|\/]{1,}/', $this->string, $operators, PREG_OFFSET_CAPTURE);

        return $operators[0];
    }

    public function getNumbers()
    {
        preg_match_all('/[0-9]{1,}/', $this->string, $numbers, PREG_OFFSET_CAPTURE);

        return $numbers[0];
    }

    public function getOperatorsByPriority()
    {
        $notPriorityOperators = [];
        $priorityOperators    = [];
        foreach ($this->getOperators() as $operator) {
            if (in_array($operator[0], $this->highPriorityOperands)) {
                array_push($priorityOperators, $operator);
            } else {
                array_push($notPriorityOperators, $operator);
            }
        }
        return array_merge($priorityOperators, $notPriorityOperators);
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

            foreach ($numbers as $number) {
                if (count($result['numbers']) == 0 && $number[1] == $operatorPosition - strlen((string)$number[0])) {
                    array_unshift($result['numbers'], $number);
                }

                if ($number[1] == $operatorPosition + 1) {
                    array_push($result['numbers'], $number);
                }
            }
        }

        return $result;
    }

    protected function prepareString(string $string): string
    {
        $string = str_replace(' ', '', $string);

        return $string;
    }
}