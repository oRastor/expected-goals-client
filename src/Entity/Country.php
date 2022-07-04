<?php


namespace ExpectedGoalsClient\Entity;


class Country
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "#{$this->id} {$this->name}";
    }
}