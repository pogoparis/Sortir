<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;

class Filtre
{
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



}