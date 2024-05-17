<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use App\Traits\CustomerData;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints\DateTime;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    use CustomerData;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\Column(length: 255)]
    private ?string $methodOfPayment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\OneToOne(mappedBy: 'order', targetEntity: Address::class)]
    #[JoinColumn(name: 'address_id', referencedColumnName: 'id')]
    private ?Address $address = null;
    #[ORM\OneToOne(mappedBy: 'order', targetEntity: DeliveryAddress::class)]
    #[JoinColumn(name: 'delivery_address_id', referencedColumnName: 'id')]
    private ?DeliveryAddress $deliveryAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastEdit = null;

    #[ORM\Column(type: "boolean")]
    private bool $carSeat = false;

    #[ORM\Column(type: "boolean")]
    private bool $dogCage = false;

    #[ORM\Column]
    private ?int $childSeat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getMethodOfPayment(): ?string
    {
        return $this->methodOfPayment;
    }

    public function setMethodOfPayment(string $methodOfPayment): self
    {
        $this->methodOfPayment = $methodOfPayment;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDeliveryAddress(): ?DeliveryAddress
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(DeliveryAddress $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTotalTime(): \DateInterval
    {
        return date_diff($this->startDate, $this->endDate);
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLastEdit(): ?\DateTimeInterface
    {
        return $this->lastEdit;
    }

    public function setLastEdit(\DateTimeInterface $lastEdit): self
    {
        $this->lastEdit = $lastEdit;

        return $this;
    }

    public function hasCarSeat(): bool
    {
        return $this->carSeat;
    }

    public function setCarSeat(bool $carSeat): void
    {
        $this->carSeat = $carSeat;
    }

    public function hasDogCage(): bool
    {
        return $this->dogCage;
    }

    public function setDogCage(bool $dogCage): void
    {
        $this->dogCage = $dogCage;
    }

    /**
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address|null $address
     */
    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }

    public function isCancelable(): bool
    {
        $now = new \DateTime();
        $startDate24HoursBefore = $this->getStartDate()->sub(new \DateInterval('P1D'));
        return $now < $startDate24HoursBefore && $this->getStatus() != 'Cancelled';
    }


    public function getChildSeat(): ?int
    {
        return $this->childSeat;
    }

    public function setChildSeat(int $childSeat): self
    {
        $this->childSeat = $childSeat;

        return $this;
    }
}
