<?php

namespace App;

use App\Interface\CalculatorInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    protected function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerForAutoconfiguration(CalculatorInterface::class)
            ->addTag('calculators');
    }
}
