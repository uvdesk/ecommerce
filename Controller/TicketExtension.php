<?php

namespace UVDesk\CommunityPackages\UVDesk\ECommerce\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use UVDesk\CommunityPackages\UVDesk\ECommerce\ECommerce;
use UVDesk\CommunityPackages\UVDesk\ECommerce\Entity\ECommerceOrderDetails;
use Webkul\UVDesk\CoreFrameworkBundle\Entity\Ticket;

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

        $requestOrderCollection = array_map('trim', explode(',', $params['orderId']));
        $eCommerceOrderDetails = $eCommerceChannel->fetchECommerceOrderDetails((array) $requestOrderCollection);

        if (!empty($eCommerceOrderDetails['orders'])) {
            $ticketRepository = $entityManager->getRepository(Ticket::class);
            $eCommerceOrderRepository = $entityManager->getRepository(ECommerceOrderDetails::class);

            $ticket = $ticketRepository->findOneById($id);
            $attachedTicketEntries = $eCommerceOrderRepository->findByTicket($ticket);
            
            // Remove previous ticket entries
            if (!empty($attachedTicketEntries)) {
                foreach ($attachedTicketEntries as $attachedTicket) {
                    $entityManager->remove($attachedTicket);
                }
            }
           
            foreach ($eCommerceOrderDetails['orders'] as $order) {
                $ecommerceOrder = new ECommerceOrderDetails();

                // Set ECom. Order Details
                $ecommerceOrder->setTicket($ticket);
                $ecommerceOrder->setOrderId($order['id']);
                $ecommerceOrder->setOrderDetails(json_encode($eCommerceOrderDetails));
        
                $entityManager->persist($ecommerceOrder);
            }

            $entityManager->flush();
            
            // Setup Response
            $response = [
                'success' => true,
                'orderDetails' => $eCommerceOrderDetails,
                'alertClass' => 'success',
                'alertMessage' => 'Success! Order Added to the ticket.',
                'collectedOrders' => $params['orderId'],
            ];
        } else {
            // Setup Response
            $response = [
                'error' => true,
                'alertClass' => "error",
                'alertMessage' => "Warning! order with given orderId doesn't exist."
            ];
        }

        return new JsonResponse($response);
    }
}
