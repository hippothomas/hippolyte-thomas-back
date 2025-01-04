<?php

namespace App;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[\Override]
    protected function build(ContainerBuilder $container): void
    {
        // fixing issue: https://github.com/doctrine/orm/issues/8893
        $container->addCompilerPass(new class implements CompilerPassInterface {
            public function process(ContainerBuilder $container): void
            {
                $container->getDefinition('doctrine.orm.default_configuration')
                    ->addMethodCall('setIdentityGenerationPreferences', [
                        [PostgreSQLPlatform::class => ClassMetadataInfo::GENERATOR_TYPE_SEQUENCE],
                    ]);
            }
        });
    }
}
