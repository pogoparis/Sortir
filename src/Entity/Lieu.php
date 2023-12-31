<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('sorties:lieux')]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Groups('sorties:lieux')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Groups('sorties:lieux')]
    private ?string $rue = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Groups('sorties:lieux')]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Groups('sorties:lieux')]
    private ?float $longitude = null;

    #[ORM\OneToMany(mappedBy: 'lieu', targetEntity: Sortie::class, orphanRemoval: true)]
    private Collection $sortie;

    #[ORM\ManyToOne(inversedBy: 'lieu')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Merci de selectionner une ville')]
    #[Groups('sorties:lieux')]
    private ?Ville $ville = null;

    public function __construct()
    {
        $this->sortie = new ArrayCollection();
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

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

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortie(): Collection
    {
        return $this->sortie;
    }

    public function addSortie(Sortie $sortie): static
    {
        if (!$this->sortie->contains($sortie)) {
            $this->sortie->add($sortie);
            $sortie->setLieu($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): static
    {
        if ($this->sortie->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getLieu() === $this) {
                $sortie->setLieu(null);
            }
        }

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function __serialize(): array
    {
        return [
            'id'=>$this->id,
            'nom'=>$this->nom,
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'rue'=>$this->rue,
            'ville'=>$this->ville,

        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id=$data['id'];
        $this->nom=$data['nom'];
        $this->latitude=$data['latitude'];
        $this->longitude=$data['longitude'];
        $this->rue=$data['rue'];
        $this->ville=$data['ville'];
    }
}
