<?php

namespace App\Entity;

use App\Repository\DeliveryAddressRepository;
use App\Traits\AddressData;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryAddressRepository::class)]
class DeliveryAddress
{
    use AddressData;

    #[ORM\OneToOne(inversedBy: 'deliveryAddress')]
    private ?Order $order = null;

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }
}
