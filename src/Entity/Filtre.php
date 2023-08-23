<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;

class Filtre
{
    /**
    * @var string
    */
    public string $nom ="";

    /**
     * @var dateTime
     */
    public DateTimeInterface $dateMin;

    /**
     * @var dateTime
     */
    public dateTime $dateMax;


}