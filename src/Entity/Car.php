<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column]
    private ?int $seats = null;

    #[ORM\Column]
    private ?int $luggage = null;

    #[ORM\Column]
    private ?int $doors = null;

    #[ORM\Column(length: 255)]
    private ?string $gear = null;

    #[ORM\Column]
    private ?int $includedKilometres = null;

    #[ORM\Column]
    private ?float $pricePerDay = null;

    #[ORM\Column]
    private ?float $pricePerWeekend = null;

    #[ORM\Column]
    private ?float $pricePerWeek = null;

    #[ORM\Column]
    private ?float $pricePerKilometre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnailURL = null;

    #[ORM\Column]
    private ?bool $isDogeCageCompatible = null;

    #[ORM\Column]
    private ?int $childSeat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): self
    {
        $this->seats = $seats;

        return $this;
    }

    public function getLuggage(): ?int
    {
        return $this->luggage;
    }

    public function setLuggage(int $luggage): self
    {
        $this->luggage = $luggage;

        return $this;
    }

    public function getDoors(): ?int
    {
        return $this->doors;
    }

    public function setDoors(int $doors): self
    {
        $this->doors = $doors;

        return $this;
    }

    public function getGear(): ?string
    {
        return $this->gear;
    }

    public function setGear(string $gear): self
    {
        $this->gear = $gear;

        return $this;
    }

    public function getIncludedKilometres(): ?int
    {
        return $this->includedKilometres;
    }

    public function setIncludedKilometres(int $includedKilometres): self
    {
        $this->includedKilometres = $includedKilometres;

        return $this;
    }

    public function getPricePerDay(): ?float
    {
        return $this->pricePerDay;
    }

    public function setPricePerDay(float $pricePerDay): self
    {
        $this->pricePerDay = $pricePerDay;

        return $this;
    }

    public function getPricePerWeekend(): ?float
    {
        return $this->pricePerWeekend;
    }

    public function setPricePerWeekend(float $pricePerWeekend): self
    {
        $this->pricePerWeekend = $pricePerWeekend;

        return $this;
    }

    public function getPricePerWeek(): ?float
    {
        return $this->pricePerWeek;
    }

    public function setPricePerWeek(float $pricePerWeek): self
    {
        $this->pricePerWeek = $pricePerWeek;

        return $this;
    }

    public function getPricePerKilometre(): ?float
    {
        return $this->pricePerKilometre;
    }

    public function setPricePerKilometre(float $pricePerKilometre): self
    {
        $this->pricePerKilometre = $pricePerKilometre;

        return $this;
    }

    public function getThumbnailURL(): ?string
    {
        if (file_exists('images/' . $this->thumbnailURL)) {
            return 'images/' . $this->thumbnailURL;
        } else {
            return 'assets/' . $this->thumbnailURL;
        }
    }

    public function setThumbnailURL(?string $thumbnailURL): self
    {
        $this->thumbnailURL = $thumbnailURL;

        return $this;
    }

    public function isIsDogeCageCompatible(): ?bool
    {
        return $this->isDogeCageCompatible;
    }

    public function setIsDogeCageCompatible(bool $isDogeCageCompatible): self
    {
        $this->isDogeCageCompatible = $isDogeCageCompatible;

        return $this;
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
