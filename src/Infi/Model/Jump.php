<?php declare(strict_types=1);

namespace AoC\Infi\Model;

class Jump implements \JsonSerializable
{
    /** @var int */
    private $right;
    /** @var int */
    private $up;
    /** @var Flat|null */
    private $to;

    public function __construct(int $right, int $up, Flat $to = null)
    {
        $this->right = $right;
        $this->up = $up;
        $this->to = $to;
    }

    public function getRight(): int
    {
        return $this->right;
    }

    public function getUp(): int
    {
        return $this->up;
    }

    public function getTo(): ?Flat
    {
        return $this->to;
    }

    public function getRequiredEnergy(): int
    {
        return $this->right + $this->up;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            $this->right,
            $this->up
        ];
    }
}
