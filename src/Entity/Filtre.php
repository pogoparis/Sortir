<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use phpDocumentor\Reflection\Types\Boolean;

class Filtre
{
    /**
     * @var bool|null
     */
    private ?bool $sortiesPassees = null;

    /**
     * @var User|null
     */
    private ?User $user = null;

    /**
     * @var Site|null
     */
    private ?Site $site = null;

    /**
    * @var string|null
    */
    private ?string $nom ="";

    /**
     * @var dateTimeInterface | null
     */
    private ?DateTimeInterface $dateMin = null;

    /**
     * @var DateTimeInterface|null
     */
    private ?DateTimeInterface $dateMax = null;


    /**
     * @var bool|null
     */
    private ?bool $organisateur = null;

    /**
     * @var bool|null
     */
    private ?bool $inscrit = null;

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateMin(): ?DateTimeInterface
    {
        return $this->dateMin;
    }

    /**
     * @param DateTimeInterface|null $dateMin
     */
    public function setDateMin(?DateTimeInterface $dateMin): void
    {
        $this->dateMin = $dateMin;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateMax(): ?DateTimeInterface
    {
        return $this->dateMax;
    }

    /**
     * @param DateTimeInterface|null $dateMax
     */
    public function setDateMax(?DateTimeInterface $dateMax): void
    {
        $this->dateMax = $dateMax;
    }

    /**
     * @return bool|null
     */
    public function getOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    /**
     * @param bool|null $organisateur
     */
    public function setOrganisateur(?bool $organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    /**
     * @return bool|null
     */
    public function getInscrit(): ?bool
    {
        return $this->inscrit;
    }

    /**
     * @param bool|null $inscrit
     */
    public function setInscrit(?bool $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Site|null
     */
    public function getSite(): ?Site
    {
        return $this->site;
    }

    /**
     * @param Site|null $site
     */
    public function setSite(?Site $site): void
    {
        $this->site = $site;
    }

    /**
     * @return bool|null
     */
    public function getSortiesPassees(): ?bool
    {
        return $this->sortiesPassees;
    }

    /**
     * @param bool|null $sortiesPassees
     */
    public function setSortiesPassees(?bool $sortiesPassees): void
    {
        $this->sortiesPassees = $sortiesPassees;
    }




}