<?php declare(strict_types=1);

namespace AoC\Infi\Model;

class Flats  implements \IteratorAggregate
{
    /** @var \Doctrine\Common\Collections\ArrayCollection */
    private $flats;

    public function __construct(Flat ...$flats)
    {
        $this->flats = new \Doctrine\Common\Collections\ArrayCollection($flats);
    }

    public function getIterator()
    {
        return $this->flats->getIterator();
    }

    public function first(): Flat
    {
        return $this->flats->first();
    }

    public function hasFlatAtPosition(int $position): bool
    {
        return (bool) $this->flats->filter(static function(Flat $flat) use ($position): bool {
             return $position === $flat->getPosition();
        })->count();
    }

    public function getFlatAtPosition(int $position): Flat
    {
        return $this->flats->filter(static function(Flat $flat) use ($position): bool {
            return $position === $flat->getPosition();
        })->first();
    }

    public function getPossibleJumps(Flat $from): Jumps
    {
        return new Jumps(...$this->flats->filter(static function(Flat $flat) use ($from): bool {
            /* Filter outer out of reach flats */
            return $flat->getPosition() > $from->getPosition() && $flat->getPosition() <= $from->getPosition() + 4 + 1;
        })->map(function(Flat $flat) use ($from): Jump {
            return $this->calculateJump($from, $flat);
        })->filter(static function(Jump $jump): bool {
            return $jump->getRight() + $jump->getUp() <= 4;
        })->toArray());
    }

    public function calculateJump(Flat $from, Flat $to): Jump
    {
        return new Jump(
            $to->getPosition() - $from->getPosition() - 1,
            max(0, $to->getHeight() - $from->getHeight()),
            $to
        );

    }
}
