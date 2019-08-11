<?php

namespace FKS\StringCalculator;

use FKS\StringCalculator\Calculators\DefaultCalculator;
use FKS\StringCalculator\Contacts\Calculator as CalculatorInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CalculatorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}