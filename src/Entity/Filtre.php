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
     * @var DateTimeInterface |null
     */
    public ?DateTimeInterface $dateMin = null;

    /**
     * @var DateTimeInterface|null
     */
    public ?DateTimeInterface $dateMax = null;


}