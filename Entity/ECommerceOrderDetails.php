<?php

namespace UVDesk\CommunityPackages\UVDesk\ECommerce\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webkul\UVDesk\CoreFrameworkBundle\Entity\Ticket;

/**
 * @ORM\Table(name="uv_pkg_uvdesk_ecommerce_ticket_order_details")
 * @ORM\Entity(repositoryClass="UVDesk\CommunityPackages\UVDesk\ECommerce\Repository\ECommerceOrderDetailsRepository")
 */
class ECommerceOrderDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $orderId;

    /**
     * @ORM\Column(type="json_array")
     */
    private $orderDetails;

    /**
     * @ORM\ManyToOne(targetEntity="Webkul\UVDesk\CoreFrameworkBundle\Entity\Ticket")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $ticket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getOrderDetails()
    {
        return $this->orderDetails;
    }

    public function setOrderDetails($orderDetails): self
    {
        $this->orderDetails = $orderDetails;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }
}
