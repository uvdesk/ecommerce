<?php

namespace UVDesk\CommunityPackages\UVDesk\ECommerce;

use UVDesk\CommunityPackages\UVDesk\ECommerce\Utils\ECommercePlatformInterface;

class ECommerce
{
    private $eCommercePlatforms = [];

    public function addECommercePlatform(ECommercePlatformInterface $eCommercePlatform, array $tags = [])
    {
        $this->eCommercePlatforms[$eCommercePlatform->getQualifiedName()] = $eCommercePlatform;

        return $this;
    }

    public function getECommercePlatforms()
    {
        return $this->eCommercePlatforms;
    }

    public function getECommercePlatformByQualifiedName($qualifiedName)
    {
        return $this->eCommercePlatforms[$qualifiedName] ?? null;
    }
}
