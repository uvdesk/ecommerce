<?php

namespace UVDesk\CommunityPackages\UVDesk\ECommerce;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webkul\UVDesk\ExtensionFrameworkBundle\Definition\Package\Package;
use Webkul\UVDesk\ExtensionFrameworkBundle\Definition\Package\PackageInterface;
use UVDesk\CommunityPackages\UVDesk\ECommerce\Utils\ECommercePlatformInterface;
use Webkul\UVDesk\ExtensionFrameworkBundle\Definition\Package\ContainerBuilderAwarePackageInterface;

class ECommercePackage extends Package implements PackageInterface, ContainerBuilderAwarePackageInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has(ECommerce::class)) {
            $eCommerceConfiguration = $container->findDefinition(ECommerce::class);

            foreach ($container->findTaggedServiceIds(ECommercePlatformInterface::class) as $reference => $tags) {
                $eCommerceConfiguration->addMethodCall('addECommercePlatform', array(new Reference($reference), $tags));
            }
        }
    }
}
