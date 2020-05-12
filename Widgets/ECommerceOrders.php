<?php

namespace UVDesk\CommunityPackages\UVDesk\ECommerce\Widgets;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment as TwigEnvironment;
use Symfony\Component\HttpFoundation\RequestStack;
use UVDesk\CommunityPackages\UVDesk\ECommerce\ECommerce;
use Webkul\UVDesk\CoreFrameworkBundle\Tickets\WidgetInterface;

class ECommerceOrders implements WidgetInterface
{
    CONST SVG = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="18px">
    <path fill-rule="evenodd" fill="rgb(51, 51, 51)" d="M19.000,-0.000 L1.000,-0.000 L1.000,2.000 L19.000,2.000 L19.000,-0.000 ZM20.000,11.000 L20.000,8.995 L19.000,3.000 L1.000,3.000 L0.000,9.000 L0.000,11.000 L1.000,11.000 L1.000,18.000 L12.000,18.000 L12.000,11.000 L17.000,11.000 L17.000,18.000 L19.000,18.000 L19.000,11.000 L20.000,11.000 ZM10.000,16.000 L3.000,16.000 L3.000,11.000 L10.000,11.000 L10.000,16.000 Z"/>
</svg>
SVG;

    public function __construct(RequestStack $requestStack, TwigEnvironment $twig, ECommerce $eCommerceConfiguration, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->requestStack = $requestStack;
        $this->eCommerceConfiguration = $eCommerceConfiguration;
        $this->entityManager = $entityManager;
    }

    public static function getIcon()
    {
        return self::SVG;
    }

    public static function getTitle()
    {
        return "ECommerce";
    }

    public static function getDataTarget()
    {
        return 'uv-ecommerce-view';
    }

    public function getTemplate()
    {
        $eCommerceChannelCollection = [];
        $request = $this->requestStack->getCurrentRequest();

        foreach ($this->eCommerceConfiguration->getECommercePlatforms() as $eCommercePlatform) {
            foreach ($eCommercePlatform->getECommerceChannelCollection() as $eCommerceChannel) {
               if($eCommerceChannel->getIsEnabled() == true) {
                    $eCommerceChannelCollection[] = [
                        'id' => $eCommerceChannel->getId(),
                        'name' => $eCommerceChannel->getName(),
                        'platform' => [
                            'id' => $eCommercePlatform->getQualifiedName(),
                            'name' => $eCommercePlatform->getName(),
                        ],
                    ];
               }
            }
        }

        $ticketId = $request->get('ticketId');
        $ticket = $this->entityManager->getRepository('UVDeskCoreFrameworkBundle:Ticket')->findOneById($ticketId);
        $eCommerceOrders = $this->entityManager->getRepository('UVDeskECommercePackage:ECommerceOrderDetails')->findByTicket($ticket);

        return $this->twig->render('@_uvdesk_extension_uvdesk_ecommerce/widgets/ecommerce-orders.html.twig', [
            'id' => $request->get('ticketId'),
            'eCommerceChannelCollection' => $eCommerceChannelCollection,
            'eCommerceOrders' => !empty($eCommerceOrders) ? current($eCommerceOrders)->getOrderDetails() : null,
        ]);
    }
}
