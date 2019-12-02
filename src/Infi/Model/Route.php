<?php declare(strict_types=1);

namespace AoC\Infi\Model;

class Route
{
    /** @var Flats */
    private $flats;
    /** @var Jumps */
    private $jumps;

    public function __construct(Flats $flats, Jumps $jumps)
    {
        $this->flats = $flats;
        $this->jumps = $jumps;
    }

    public function getFlats(): Flats
    {
        return $this->flats;
    }

    public function getJumps(): Jumps
    {
        return $this->jumps;
    }
}
