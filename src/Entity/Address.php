<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use App\Traits\AddressData;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    use AddressData;

    #[ORM\OneToOne(inversedBy: 'address')]
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
