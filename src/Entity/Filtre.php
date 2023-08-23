<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;

class Filtre
{
    /**
    * @var string|null
    */
    public ?string $nom ="";

    /**
     * @var dateTimeInterface | null
     */
    public ?DateTimeInterface $dateMin = null;

    /**
     * @var dateTimeInterface | null
     */
    public ?DateTimeInterface $dateMax = null;

    /**
     * @var bool|null
     */
    public ?bool $organisateur = null;
}