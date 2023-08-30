<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('sorties:ville')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('sorties:ville')]
    private ?string $nom = null;

    #[ORM\Column(length: 8)]
    #[Groups('sorties:ville')]
    private ?string $codePostal = null;

    #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Lieu::class, orphanRemoval: true)]
    private Collection $lieu;

    #[ORM\Column(nullable: true)]
    #[Groups('sorties:ville')]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    #[Groups('sorties:ville')]
    private ?float $latitude = null;

    public function __construct()
    {
        $this->lieu = new ArrayCollection();
    }

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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection<int, Lieu>
     */
    public function getLieu(): Collection
    {
        return $this->lieu;
    }

    public function addLieu(Lieu $lieu): static
    {
        if (!$this->lieu->contains($lieu)) {
            $this->lieu->add($lieu);
            $lieu->setVille($this);
        }

        return $this;
    }

    public function removeLieu(Lieu $lieu): static
    {
        if ($this->lieu->removeElement($lieu)) {
            // set the owning side to null (unless already changed)
            if ($lieu->getVille() === $this) {
                $lieu->setVille(null);
            }
        }

        return $this;
    }

    public function __serialize(): array
    {
        return [
            'id'=>$this->id,
            'nom'=>$this->nom,
            'codePostal'=>$this->codePostal,
            'latitude'=> $this->latitude,
            'longitude'=>$this->longitude
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id=$data['id'];
        $this->nom=$data['nom'];
        $this->codePostal=$data['codePostal'];
        $this->latitude=$data['latitude'];
        $this->longitude=$data['longitude'];
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

}
