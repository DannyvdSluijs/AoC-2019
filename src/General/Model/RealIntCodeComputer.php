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

    public function run(OutputInterface $output): int
    {
        $commands = array_chunk($this->puzzleInput, 4);

        $i = 0;
        foreach ($commands as $command) {
            $command[0] = $this->puzzleInput[4 * $i++];

            if ($command[0] === '99') {
                return $this->puzzleInput[0];
            }

            $inputOne = $this->puzzleInput[$command[1]];
            $inputTwo = $this->puzzleInput[$command[2]];

            switch ($command[0]) {
                case 1:
                    $this->puzzleInput[$command[3]] = $inputOne + $inputTwo;
                    break;
                case 2:
                    $this->puzzleInput[$command[3]] = $inputOne * $inputTwo;
                    break;
                default:
                    throw new \Exception("No such opcode: $command[0]");
            }
        }

        return $this->puzzleInput[0];
    }


}
