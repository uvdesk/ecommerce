<?php

namespace UVDesk\CommunityPackages\UVDesk\ECommerce\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use UVDesk\CommunityPackages\UVDesk\ECommerce\ECommerce;
use UVDesk\CommunityPackages\UVDesk\ECommerce\Entity\ECommerceOrderDetails;

class TicketExtension
{
    public function integrateECommerceOrderDetails($id, Request $request, ECommerce $eCommerceConfiguration, EntityManagerInterface $entityManager)
    {
        $params = json_decode($request->getContent(), true);
        $eCommercePlatform = $eCommerceConfiguration->getECommercePlatformByQualifiedName($params['platform']);

        if (empty($eCommercePlatform)) {
            return new JsonResponse([
                'success' => false,
                'alertClass' => 'error',
                'alertMessage' => 'Unable to retrieve channel details.',
            ], 404);
        } else {
            $eCommerceChannel = $eCommercePlatform->getECommerceChannel($params['channelId']);

            if (empty($eCommerceChannel)) {
                return new JsonResponse([
                    'success' => false,
                    'alertClass' => 'error',
                    'alertMessage' => 'Unable to retrieve channel details.',
                ], 404);
            }
        }

        $eCommerceOrderDetails = $eCommerceChannel->fetchECommerceOrderDetails((array) $params['orderId']);
        
        $ticketRepository = $entityManager->getRepository('UVDeskCoreFrameworkBundle:Ticket');
        $eCommerceOrderRepository = $entityManager->getRepository('UVDeskECommercePackage:ECommerceOrderDetails');

        $ticket = $ticketRepository->findOneById($id);

        // // Retrieve any existing ticket order else create one
        // $existingOrders = $eCommerceOrderRepository->findByTicket($ticket);

        // if (empty($existingOrders)) {
        //     $orderExistsFlag = 1;
        // }

        $ecommerceOrder = new ECommerceOrderDetails();

        // Set ECom. Order Details
        $ecommerceOrder->setTicket($ticket);
        $ecommerceOrder->setOrderId($params['orderId']);
        $ecommerceOrder->setOrderDetails(json_encode($eCommerceOrderDetails));

        $entityManager->persist($ecommerceOrder);
        $entityManager->flush();

        // Setup Response
        $response = [
            'success' => true,
            'orderDetails' => $eCommerceOrderDetails,
            'alertClass' => 'success',
            'alertMessage' => 'Success! Order updated successfully.',
            'collectedOrders' => $params['orderId'],
        ];

        return new JsonResponse($response);
    }
}
