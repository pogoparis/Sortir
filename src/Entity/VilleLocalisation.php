<?php

namespace App\Entity;

use App\Repository\VilleLocalisationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VilleLocalisationRepository::class)]
class VilleLocalisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('local:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('local:read')]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups('local:read')]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Groups('local:read')]
    private ?float $longitude = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function __serialize(): array
    {
        return [
            'id'=>$this->id,
            'nom'=>$this->nom,
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id=$data['id'];
        $this->nom=$data['nom'];
        $this->latitude=$data['latitude'];
        $this->longitude=$data['longitude'];
    }


}
