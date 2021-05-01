<?php

namespace Mybit\GoogleDistanceMatrix\Responses;

class Row
{
    private $elements;

    public function __construct($elements)
    {
        $this->elements = $elements;
    }

    public function getElements() : array
    {
        return $this->elements;
    }
}
