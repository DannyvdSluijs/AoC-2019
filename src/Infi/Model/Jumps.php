<?php declare(strict_types=1);

namespace AoC\Infi\Model;

class Jumps implements \IteratorAggregate
{
    /** @var Jumps[] */
    private $jumps;

    public function __construct(Jump  ...$jumps)
    {
        $this->jumps = $jumps;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->jumps);
    }
}
