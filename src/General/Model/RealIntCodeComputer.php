<?php declare(strict_types=1);

namespace AoC\General\Model;

use SplQueue;
use Symfony\Component\Console\Output\OutputInterface;

class RealIntCodeComputer
{
    private const PARAMETER_POSITION_MODE = 0;
    private const PARAMETER_IMMEDIATE_MODE = 1;
    private const PARAMETER_RELATIVE_MODE = 2;

    private const OPCODE_HALT = 99;
    private const OPCODE_ADDITION = 1;
    private const OPCODE_MULTIPLICATION = 2;
    private const OPCODE_READ_INPUT = 3;
    private const OPCODE_WRITE_OUTPUT = 4;
    private const OPCODE_JUMP_IF_TRUE = 5;
    private const OPCODE_JUMP_IF_FALSE = 6;
    private const OPCODE_LESS_THEN = 7;
    private const OPCODE_EQUALS = 8;
    private const OPCODE_SET_RELATIVE_BASE = 9;

    /** @var array */
    private $memory = [];
    /** @var bool */
    private $halted = false;
    /** @var int */
    private $memoryPointer = 0;
    /** @var int */
    private $relativeBase;
    /** @var OutputInterface */
    private $output;

    public function __construct(string $program, int $relativeBase = 0)
    {
        foreach (explode(',', $program) as $key => $value) {
            $this->memory[(string) $key] = $value;
        }
        $this->relativeBase = $relativeBase;
    }

    public function setAddressValue(string $address, string $value): void
    {
        $this->memory[$address] = $value;
    }

    public function run(OutputInterface $output, splQueue $in, splQueue $out, callable $outPutCallback = null): void
    {
        $this->output = $output;

        if ($this->halted) {
            throw new \Exception('Computer halted');
        }

        while(true) {
            $command = $this->memory[$this->memoryPointer];
            $opcode = $command % 100;
            $paramOneMode = ($command - $opcode) / 100 % 10;
            $paramTwoMode = ($command - $opcode) / 1000 % 10;
            $paramThreeMode = ($command - $opcode) / 10000 % 10;

            if ($opcode === self::OPCODE_HALT) {
                $this->halted = true;
                return;
            }

            $paramOnePointer = $this->resolvePointer($paramOneMode, 1);
            $paramTwoPointer = $this->resolvePointer($paramTwoMode, 2);
            $paramThreePointer = $this->resolvePointer($paramThreeMode, 3);
            $paramOne = $this->readMemory($paramOnePointer);
            $paramTwo = $this->readMemory($paramTwoPointer);

            switch ($opcode) {
                case self::OPCODE_ADDITION:
                    $this->memory[$paramThreePointer] = bcadd($paramOne, $paramTwo);
                    $this->memoryPointer += 4;
                    break;
                case self::OPCODE_MULTIPLICATION:
                    $this->memory[$paramThreePointer] = bcmul($paramOne, $paramTwo);
                    $this->memoryPointer += 4;
                    break;
                case self::OPCODE_READ_INPUT:
                    $this->memory[$paramOnePointer] = $in->dequeue();
                    $this->memoryPointer += 2;
                    break;
                case self::OPCODE_WRITE_OUTPUT:
                    $this->memoryPointer += 2;
                    $out->enqueue($paramOne);
                    if ($outPutCallback !== null) {
                        $outPutCallback();
                    }
                    break;
                case self::OPCODE_JUMP_IF_TRUE:
                    if (bccomp($paramOne, '0') !== 0) {
                        $this->memoryPointer = $paramTwo;
                        break;
                    }
                    $this->memoryPointer += 3;
                    break;
                case self::OPCODE_JUMP_IF_FALSE:
                    if (bccomp($paramOne, '0') === 0) {
                        $this->memoryPointer = (int) $paramTwo;
                        break;
                    }
                    $this->memoryPointer += 3;
                    break;
                case self::OPCODE_LESS_THEN:
                    if (bccomp($paramOne, $paramTwo) === -1) {
                        $this->memory[$paramThreePointer] = '1';
                    } else {
                        $this->memory[$paramThreePointer] = '0';
                    }
                    $this->memoryPointer += 4;
                    break;
                case self::OPCODE_EQUALS:
                    if (bccomp($paramOne, $paramTwo) === 0) {
                        $this->memory[$paramThreePointer] = '1';
                    } else {
                        $this->memory[$paramThreePointer] = '0';
                    }
                    $this->memoryPointer += 4;
                    break;
                case self::OPCODE_SET_RELATIVE_BASE:
                    $this->relativeBase += (int) $paramOne;
                    $this->memoryPointer += 2;
                    break;
                default:
                    throw new \Exception("No such opcode: $opcode");
            }
        }
    }

    private function resolvePointer(int $mode, $offset): string
    {
        switch ($mode) {
            case self::PARAMETER_POSITION_MODE:
                return $this->readMemory((string) ($this->memoryPointer + $offset));
            case self::PARAMETER_IMMEDIATE_MODE:
                return (string) ($this->memoryPointer + $offset);
            case self::PARAMETER_RELATIVE_MODE:
                return (string) ((int) $this->readMemory((string) ($this->memoryPointer + $offset)) + $this->relativeBase);
            default:
                throw new \Exception("Bad parameter mode: $mode");
        }
    }

    public function isHalted(): bool
    {
        return $this->halted;
    }

    private function readMemory(string $pointer): string
    {
        if (! array_key_exists($pointer, $this->memory)) {
            return '0';
        }

        return (string) $this->memory[$pointer];
    }
}
