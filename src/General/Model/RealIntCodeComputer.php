<?php declare(strict_types=1);

namespace AoC\General\Model;

use Symfony\Component\Console\Output\OutputInterface;

class RealIntCodeComputer
{
    private $puzzleInput;

    public function __construct(string $puzzleInput)
    {
        $this->puzzleInput = explode(',', $puzzleInput);
    }

    public function setAddressValue(int $address, int $value): void
    {
        $this->puzzleInput[$address] = $value;
    }

    public function run(OutputInterface $output, int $programInput): int
    {
        $i = 0;
        $len = count($this->puzzleInput);
        while($i <= $len) {
            $command = sprintf('%05s', $this->puzzleInput[$i]);
            list($modeParam3, $modeParam2, $modeParam1, $opcode1, $opcode2) = str_split($command);
            $opcode = "$opcode1$opcode2";

            $output->writeln("I: $i; Command: $command; Opcode: $opcode", OutputInterface::VERBOSITY_VERBOSE);

            if ($opcode === '99') {
                return (int) $this->puzzleInput[0];
            }

            switch ($opcode) {
                case '01':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 1]] : $this->puzzleInput[$i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 2]] : $this->puzzleInput[$i + 2]);
                    $position = $this->puzzleInput[$i + 3];

                    $this->puzzleInput[$position] = $paramOne + $paramTwo;
                    $output->writeln("Adding $paramOne with $paramTwo; Storing at $position");
                    $i += 4;
                    break;
                case '02':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 1]] : $this->puzzleInput[$i + 1]);
                    $paramTwo = ($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 2]] : $this->puzzleInput[$i + 2];
                    $position = $this->puzzleInput[$i + 3];

                    $this->puzzleInput[$position] = $paramOne * $paramTwo;
                    $output->writeln("Multiply $paramOne with $paramTwo; Storing at $position");
                    $i += 4;
                    break;
                case '03':
                    $position = $this->puzzleInput[$i + 1];
                    $this->puzzleInput[$position] = $programInput;
                    $output->writeln("Reading from input: $programInput; Storing at $position");
                    $i += 2;
                    break;
                case '04':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 1]] : $this->puzzleInput[$i + 1]);
                    $output->writeln("Diagnostic code $paramOne");
                    $i += 2;
                    break;
                case '05':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 1]] : $this->puzzleInput[$i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 2]] : $this->puzzleInput[$i + 2]);
                    $output->writeln("Jump if true; Param one: $paramOne; Param two: $paramTwo");
                    if ($paramOne !== 0) {
                        $i = $paramTwo;
                        break;
                    }
                    $i += 3;
                    break;
                case '06':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 1]] : $this->puzzleInput[$i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 2]] : $this->puzzleInput[$i + 2]);
                    $output->writeln("Jump if false; Param one: $paramOne; Param two: $paramTwo");
                    if ($paramOne === 0) {
                        $i = $paramTwo;
                        break;
                    }
                    $i += 3;
                    break;
                case '07':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 1]] : $this->puzzleInput[$i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 2]] : $this->puzzleInput[$i + 2]);
                    $paramThree = (int) $this->puzzleInput[$i + 3];
                    $output->writeln("Less than; Param one: $paramOne; Param two: $paramTwo; Param three: $paramThree");
                    if ($paramOne < $paramTwo) {
                        $this->puzzleInput[$paramThree] = 1;
                    } else {
                        $this->puzzleInput[$paramThree] = 0;
                    }
                    $i += 4;
                    break;
                case '08':
                    $paramOne = (int) (($modeParam1 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 1]] : $this->puzzleInput[$i + 1]);
                    $paramTwo = (int) (($modeParam2 === '0') ? $this->puzzleInput[$this->puzzleInput[$i + 2]] : $this->puzzleInput[$i + 2]);
                    $paramThree = (int) $this->puzzleInput[$i + 3];
                    $output->writeln("Equals; Param one: $paramOne; Param two: $paramTwo; Param three: $paramThree");
                    if ($paramOne === $paramTwo) {
                        $this->puzzleInput[$paramThree] = 1;
                    } else {
                        $this->puzzleInput[$paramThree] = 0;
                    }
                    $i += 4;
                    break;
                default:
                    throw new \Exception("No such opcode: $opcode");
            }
        }

        return (int) $this->puzzleInput[0];
    }


}
