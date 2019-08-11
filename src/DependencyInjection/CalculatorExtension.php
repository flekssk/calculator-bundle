<?php

namespace FKS\StringCalculator\DependencyInjection;

use Exception;
use FKS\StringCalculator\Contacts\Calculator;
use FKS\StringCalculator\Contacts\Result;
use FKS\StringCalculator\Exceptions\CalculatorNotFound;
use FKS\StringCalculator\Exceptions\ResultClassNotFound;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CalculatorExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration((new Configuration()), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yaml');


        $container->addAliases(
            [
                Calculator::class => $this->getCalculatorAccessor($config['options']['calculatorAlias']),
                Result::class     => $this->getResultAccessor($config['options']['resultAlias']),
            ]
        );
    }

    public function getCalculatorAccessor($calculatorAlias)
    {
        $class = 'FKS\\StringCalculator\\Calculators\\' . ucfirst($calculatorAlias) . 'Calculator';

        if (!class_exists($class)) {
            throw new CalculatorNotFound('Calculator ' . $calculatorAlias . ' doesn`t exists');
        }

        return $class;
    }

    public function getResultAccessor($resultAlias)
    {
        $class = 'FKS\\StringCalculator\\Calculators\\' . ucfirst($resultAlias) . 'Calculator';

        if (!class_exists($class)) {
            throw new ResultClassNotFound('Calculator ' . $resultAlias . ' doesn`t exists');
        }

        return $class;
    }
}