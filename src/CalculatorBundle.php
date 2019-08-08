<?php

namespace FKS\StringCalculator;

use FKS\stringCalculator\Calculators\DefaultCalculator;
use FKS\stringCalculator\Contacts\Calculator as CalculatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CalculatorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->register(CalculatorInterface::class, DefaultCalculator::class);
    }
}