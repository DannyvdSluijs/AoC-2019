<?php declare(strict_types=1);

namespace AoC\Infi\Model;

class Flat
{
    /** @var int */
    private $position;
    /** @var int */
    private $height;

    public function __construct(int $position, int $height)
    {
        $this->position = $position;
        $this->height = $height;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function __toString()
    {
        return "[$this->position, $this->height]";
    }


}
