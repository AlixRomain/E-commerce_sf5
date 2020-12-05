<?php

namespace App\Classe;


use App\Entity\Category;

Class Search
{
    /**
     * @var string
     */
    public $string ='';

    /**
     * @var category[]
     */
    public $categories = [];

    public function __toString()
    {
        return $this->string;
    }

}