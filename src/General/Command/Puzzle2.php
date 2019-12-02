<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle2 extends Command
{
    protected static $defaultName = 'puzzle-2';

    /**
     * Answer part one 3101844
     * Answer part two: .. Wrong: 655200
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
//        $computer = new RealIntCodeComputer($this->getPuzzleInput());
//        /* Part one */
//        $computer->setAddressValue(1, 12);
//        $computer->setAddressValue(2, 2);
//
//        $output->writeln($computer->run($output));
//        return 1;

        for ($x = 1; $x < 99; $x++) {
            for ($y = 1; $y < 99; $y++) {
                $computer = new RealIntCodeComputer($this->getPuzzleInput());
                $computer->setAddressValue(1, $x);
                $computer->setAddressValue(2, $y);
                $output->writeln("Running with: Noun: $x; Verb: $y;", OutputInterface::VERBOSITY_VERBOSE);
                $result = $computer->run($output);
                if ($result === 19690720) {
                    $answer = 100 * $x + $y;
                    $output->writeln("Noun: $x; Verb: $y; Answer: $answer");
                    return 1;
                }
            }
        }

        return 1;
    }

    private function getPuzzleInput(): string
    {
//        return '1,0,0,0,99'; /* Becomes 2,0,0,0,99 */
//        return '2,3,0,3,99'; /* becomes 2,3,0,6,99 */
//        return '2,4,4,5,99,0'; /* becomes 2,4,4,5,99,9801 */
//        return '1,1,1,4,99,5,6,0,99'; /* becomes 30,1,1,4,2,5,6,0,99. */
//        return '1,9,10,3,2,3,11,0,99,30,40,50';
        return '1,0,0,3,1,1,2,3,1,3,4,3,1,5,0,3,2,6,1,19,1,5,19,23,2,9,23,27,1,6,27,31,1,31,9,35,2,35,10,39,1,5,39,43,2,43,9,47,1,5,47,51,1,51,5,55,1,55,9,59,2,59,13,63,1,63,9,67,1,9,67,71,2,71,10,75,1,75,6,79,2,10,79,83,1,5,83,87,2,87,10,91,1,91,5,95,1,6,95,99,2,99,13,103,1,103,6,107,1,107,5,111,2,6,111,115,1,115,13,119,1,119,2,123,1,5,123,0,99,2,0,14,0';
    }

    /* Not:
        337042
    */
}
