<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use SplDoublyLinkedList;
use SplQueue;
use SplStack;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle16Part1 extends Command
{
    protected static $defaultName = 'puzzle-16-part-1';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $numbers = str_split($this->getPuzzleInput());
        $size = count($numbers);
        $basePattern = [0, 1, 0, -1];
        $patterns = [];
        $maxPhases = 100;

        for ($i = 0; $i < $size; $i++) { /* Loop for the width of the input */
            $pattern = [];
            while(count($pattern) < $size + 1) {
                foreach ($basePattern as $pat) {
                    for ($x = 0; $x <= $i; $x++) {
                        $pattern[] = $pat;
                    }
                }
            }
            array_shift($pattern);

            $patterns[$i] = $pattern;
        }

        for ($phase = 1; $phase <= $maxPhases; $phase++) { /* Loop for the phases */
            $result = [];
            for ($i = 0; $i < $size; $i++) { /* Loop for the width of the input */
                $pattern = $patterns[$i];
                $sum = 0;
                foreach ($numbers as $key => $number) { /* Loop over each number */
                    $sum += $number * $pattern[$key];
                }
                $result[$i] = abs($sum) % 10;
            }
            $numbers = $result;
        }

        $output->writeln('First eight characters on the result are: ' . substr(implode($result), 0, 8));
        return 1;
    }

    private function getPuzzleInput(): string
    {
        return '59756772370948995765943195844952640015210703313486295362653878290009098923609769261473534009395188480864325959786470084762607666312503091505466258796062230652769633818282653497853018108281567627899722548602257463608530331299936274116326038606007040084159138769832784921878333830514041948066594667152593945159170816779820264758715101494739244533095696039336070510975612190417391067896410262310835830006544632083421447385542256916141256383813360662952845638955872442636455511906111157861890394133454959320174572270568292972621253460895625862616228998147301670850340831993043617316938748361984714845874270986989103792418940945322846146634931990046966552';
    }
}
