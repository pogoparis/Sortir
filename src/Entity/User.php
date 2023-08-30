<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['email'], message: 'Cette email est déjà utilisé')]
#[UniqueEntity(fields: ['pseudo'], message: 'Il y a déjà un utilisateur avec le même pseudo')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern:"/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/", message:"Format de l'email non valide")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotCompromisedPassword()]
    private ?string $password = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_.@$&%-]+$/")]
    private ?string $pseudo = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^(?:[^\d\W][\-\s\']{0,1}){2,50}$/i")]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^(?:[^\d\W][\-\s\']{0,1}){2,50}$/i")]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?bool $isAdmin = null;

    #[ORM\Column]
    private ?bool $isActif = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $siteEni = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Sortie::class, orphanRemoval: true)]
    private Collection $sortiesOrganisateur;

    #[ORM\ManyToMany(targetEntity: Sortie::class, mappedBy: 'participants')]
    private Collection $sortiesParticipants;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName= null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 10, minMessage: "9 chiffres mini sans le premier (0) ", maxMessage: "10 chiffres max")]
    #[Assert\Regex(pattern:"/^(\(0\))?[0-9]{10}$/", message:"Doit commencer par (0) et avoir 10 chiffres")]
    private ?string $telephone = null;

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function __construct()
    {
        $this->sortiesOrganisateur = new ArrayCollection();
        $this->sortiesParticipants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }



    public function isIsAdmin(): ?bool
    {
        if ($this->isAdmin ===true){
            $this->setRoles([]);
        }else {
            $this->setRoles(["ROLE_ADMIN"]);
        }
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function isIsActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): static
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function getSiteEni(): ?Site
    {
        return $this->siteEni;
    }

    public function setSiteEni(?Site $siteEni): static
    {
        $this->siteEni = $siteEni;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @param File|null $imageFile
     */
    public function setImageFile(?File $imageFile): void
    {

        $this->imageFile = $imageFile;
        if(null !== $imageFile){
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesOrganisateur(): Collection
    {
        return $this->sortiesOrganisateur;
    }

    public function addSorty(Sortie $sorty): static
    {
        if (!$this->sortiesOrganisateur->contains($sorty)) {
            $this->sortiesOrganisateur->add($sorty);
            $sorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): static
    {
        if ($this->sortiesOrganisateur->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getOrganisateur() === $this) {
                $sorty->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesParticipants(): Collection
    {
        return $this->sortiesParticipants;
    }

    public function addSortiesParticipant(Sortie $sortiesParticipant): static
    {
        if (!$this->sortiesParticipants->contains($sortiesParticipant)) {
            $this->sortiesParticipants->add($sortiesParticipant);
            $sortiesParticipant->addParticipant($this);
        }

        return $this;
    }

    public function removeSortiesParticipant(Sortie $sortiesParticipant): static
    {
        if ($this->sortiesParticipants->removeElement($sortiesParticipant)) {
            $sortiesParticipant->removeParticipant($this);
        }

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): static
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function __serialize(): array
    {
        return [
            'id'=>$this->id,
            'siteEni'=>$this->siteEni,
            'email'=>$this->email,
            'roles'=>$this->roles,
            'password'=>$this->password,
            'pseudo'=>$this->pseudo,
            'nom'=>$this->nom,
            'prenom'=>$this->prenom,
            'telephone'=>$this->telephone,
            'isAdmin'=>$this->isAdmin,
            'isActif'=>$this->isActif,
            'isVerified'=>$this->isVerified,
            'imageName'=>$this->imageName,
            'imageSize'=>$this->imageSize,
            'updatedAt'=>$this->updatedAt,

        ];
    }

    public function __unserialize(array $data): void
    {

        $this->updatedAt = $data['updatedAt'];
        $this->imageSize = $data['imageSize'];
        $this->imageName = $data['imageName'];
        $this->isVerified = $data['isVerified'];
        $this->isActif = $data['isActif'];
        $this->isAdmin = $data['isAdmin'];
        $this->telephone = $data['telephone'];
        $this->prenom = $data['prenom'];
        $this->nom = $data['nom'];
        $this->pseudo = $data['pseudo'];
        $this->password = $data['password'];
        $this->email = $data['email'];
        $this->roles = $data['roles'];
        $this->siteEni = $data['siteEni'];
        $this->id = $data['id'];
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }


}
