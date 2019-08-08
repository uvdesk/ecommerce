<?php

namespace UVDesk\CommunityPackages\UVDesk\ECommerce\Utils;

interface ECommercePlatformInterface
{
    public function getQualifiedName() : string;

    public function getName() : string;

    public function getDescription() : string;

    public function getECommerceChannel($id) : ?ECommerceChannelInterface;

    public function getECommerceChannelCollection() : array;
}
