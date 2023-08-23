<?php

namespace App\Entity;

use DateTime;

class Filtre
{
    /**
    * @var string
    */
    public string $nom ="";

    /**
     * @var dateTime
     */
    public dateTime $dateMin;

    /**
     * @var dateTime
     */
    public dateTime $dateMax;


}