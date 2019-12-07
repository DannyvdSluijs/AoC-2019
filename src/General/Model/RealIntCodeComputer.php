<?php declare(strict_types=1);

namespace AoC\General\Model;

use SplQueue;
use Symfony\Component\Console\Output\OutputInterface;

class RealIntCodeComputer
{
    private $puzzleInput;
    /** @var bool */
    private $halted = false;
    private $i =0;

    public function __construct(string $puzzleInput)
    {
        $this->puzzleInput = explode(',', $puzzleInput);
    }

    public function setAddressValue(int $address, int $value): void
    {
        $this->puzzleInput[$address] = $value;
    }

    public function run(OutputInterface $output, splQueue $in, splQueue $out): void
    {
        if ($this->halted) {
            throw new \Exception('Computer halted');
        }

        $len = count($this->puzzleInput);
        while($this->i <= $len) {
            $command = sprintf('%05s', $this->puzzleInput[$this->i]);
            list($modeParam3, $modeParam2, $modeParam1, $opcode1, $opcode2) = str_split($command);
            $opcode = "$opcode1$opcode2";

            $output->writeln("I: $this->i; Command: $command; Opcode: $opcode", OutputInterface::VERBOSITY_VERBOSE);

            if ($opcode === '99') {
                $this->halted = true;
                return;
            }

            switch ($opcode) {
                case '01':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 1]] : $this->puzzleInput[$this->i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 2]] : $this->puzzleInput[$this->i + 2]);
                    $position = $this->puzzleInput[$this->i + 3];

                    $this->puzzleInput[$position] = $paramOne + $paramTwo;
                    $output->writeln("Adding $paramOne with $paramTwo; Storing at $position", OutputInterface::VERBOSITY_VERBOSE);
                    $this->i += 4;
                    break;
                case '02':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 1]] : $this->puzzleInput[$this->i + 1]);
                    $paramTwo = ($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 2]] : $this->puzzleInput[$this->i + 2];
                    $position = $this->puzzleInput[$this->i + 3];

                    $this->puzzleInput[$position] = $paramOne * $paramTwo;
                    $output->writeln("Multiply $paramOne with $paramTwo; Storing at $position", OutputInterface::VERBOSITY_VERBOSE);
                    $this->i += 4;
                    break;
                case '03':
                    $position = $this->puzzleInput[$this->i + 1];
                    $inp = $in->dequeue();
                    $this->puzzleInput[$position] = $inp;
                    $output->writeln("Reading from input: $inp; Storing at $position", OutputInterface::VERBOSITY_VERBOSE);
                    $this->i += 2;
                    break;
                case '04':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 1]] : $this->puzzleInput[$this->i + 1]);
                    $output->writeln("Diagnostic code $paramOne", OutputInterface::VERBOSITY_VERBOSE);
                    $this->i += 2;
                    $out->enqueue($paramOne);
                    return;
                    break;
                case '05':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 1]] : $this->puzzleInput[$this->i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 2]] : $this->puzzleInput[$this->i + 2]);
                    $output->writeln("Jump if true; Param one: $paramOne; Param two: $paramTwo", OutputInterface::VERBOSITY_VERBOSE);
                    if ($paramOne !== 0) {
                        $this->i = $paramTwo;
                        break;
                    }
                    $this->i += 3;
                    break;
                case '06':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 1]] : $this->puzzleInput[$this->i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 2]] : $this->puzzleInput[$this->i + 2]);
                    $output->writeln("Jump if false; Param one: $paramOne; Param two: $paramTwo", OutputInterface::VERBOSITY_VERBOSE);
                    if ($paramOne === 0) {
                        $this->i = $paramTwo;
                        break;
                    }
                    $this->i += 3;
                    break;
                case '07':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 1]] : $this->puzzleInput[$this->i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 2]] : $this->puzzleInput[$this->i + 2]);
                    $paramThree = (int) $this->puzzleInput[$this->i + 3];
                    $output->writeln("Less than; Param one: $paramOne; Param two: $paramTwo; Param three: $paramThree", OutputInterface::VERBOSITY_VERBOSE);
                    if ($paramOne < $paramTwo) {
                        $this->puzzleInput[$paramThree] = 1;
                    } else {
                        $this->puzzleInput[$paramThree] = 0;
                    }
                    $this->i += 4;
                    break;
                case '08':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 1]] : $this->puzzleInput[$this->i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$this->i + 2]] : $this->puzzleInput[$this->i + 2]);
                    $paramThree = (int) $this->puzzleInput[$this->i + 3];
                    $output->writeln("Equals; Param one: $paramOne; Param two: $paramTwo; Param three: $paramThree", OutputInterface::VERBOSITY_VERBOSE);
                    if ($paramOne === $paramTwo) {
                        $this->puzzleInput[$paramThree] = 1;
                    } else {
                        $this->puzzleInput[$paramThree] = 0;
                    }
                    $this->i += 4;
                    break;
                default:
                    throw new \Exception("No such opcode: $opcode");
            }
        }
    }

    public function isHalted(): bool
    {
        return $this->halted;
    }
}
