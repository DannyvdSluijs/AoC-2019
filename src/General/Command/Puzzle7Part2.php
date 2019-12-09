<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle7Part2 extends Command
{
    protected static $defaultName = 'puzzle-7-part-2';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $max = 0;
        $sequence = '';
        for ($p1 = 5; $p1 <= 9; $p1++) {
            for ($p2 = 5; $p2 <= 9; $p2++) {
                for ($p3 = 5; $p3 <= 9; $p3++) {
                    for ($p4 = 5; $p4 <= 9; $p4++) {
                        for ($p5 = 5; $p5 <= 9; $p5++) {
                            if (count(array_unique([$p1, $p2, $p3, $p4, $p5])) !== 5) {
                                continue;
                            }

                            $output->writeln("Phase setting sequence $p1,$p2,$p3,$p4,$p5");

                            $amplifierOne = new RealIntCodeComputer($this->getPuzzleInput());
                            $amplifierTwo = new RealIntCodeComputer($this->getPuzzleInput());
                            $amplifierThree = new RealIntCodeComputer($this->getPuzzleInput());
                            $amplifierFour = new RealIntCodeComputer($this->getPuzzleInput());
                            $amplifierFive = new RealIntCodeComputer($this->getPuzzleInput());

                            $a = new \SplQueue();
                            $a->enqueue((string) $p1);
                            $a->enqueue('0');
                            $b = new \SplQueue();
                            $b->enqueue((string) $p2);
                            $c = new \SplQueue();
                            $c->enqueue((string) $p3);
                            $d = new \SplQueue();
                            $d->enqueue((string) $p4);
                            $e = new \SplQueue();
                            $e->enqueue((string) $p5);

                            while (! $amplifierFive->isHalted()) {
                                $amplifierOne->run($output, $a, $b);
                                $amplifierTwo->run($output, $b, $c);
                                $amplifierThree->run($output, $c, $d);
                                $amplifierFour->run($output, $d, $e);
                                $amplifierFive->run($output, $e, $a);
                            }

                            $result = $a->dequeue();
                            if ($result > $max) {
                                $output->writeln('Found new max ' . $result);
                                $max = $result;
                                $sequence = implode(',' ,[$p1, $p2, $p3, $p4, $p5]);
                            }
                        }
                    }
                }
            }
        }

        $output->writeln("Answer $max with $sequence");
        return 1;
    }

    private function getPuzzleInput(): string
    {
//        return '3,26,1001,26,-4,26,3,27,1002,27,2,27,1,27,26,27,4,27,1001,28,-1,28,1005,28,6,99,0,0,5';
//        return '3,52,1001,52,-5,52,3,53,1,52,56,54,1007,54,5,55,1005,55,26,1001,54,-5,54,1105,1,12,1,53,54,53,1008,54,0,55,1001,55,1,55,2,53,55,53,4,53,1001,56,-1,56,1005,56,6,99,0,0,0,0,10';
        return '3,8,1001,8,10,8,105,1,0,0,21,38,47,64,89,110,191,272,353,434,99999,3,9,101,4,9,9,102,3,9,9,101,5,9,9,4,9,99,3,9,1002,9,5,9,4,9,99,3,9,101,2,9,9,102,5,9,9,1001,9,5,9,4,9,99,3,9,1001,9,5,9,102,4,9,9,1001,9,5,9,1002,9,2,9,1001,9,3,9,4,9,99,3,9,102,2,9,9,101,4,9,9,1002,9,4,9,1001,9,4,9,4,9,99,3,9,101,1,9,9,4,9,3,9,101,1,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,101,2,9,9,4,9,3,9,101,1,9,9,4,9,3,9,1001,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,101,2,9,9,4,9,99,3,9,101,2,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,101,2,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,101,2,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,101,2,9,9,4,9,3,9,1001,9,2,9,4,9,3,9,102,2,9,9,4,9,99,3,9,1001,9,2,9,4,9,3,9,1001,9,2,9,4,9,3,9,101,1,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,1001,9,1,9,4,9,3,9,1002,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,101,1,9,9,4,9,3,9,101,1,9,9,4,9,99,3,9,102,2,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,1001,9,1,9,4,9,3,9,1002,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,1001,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,1002,9,2,9,4,9,99,3,9,101,1,9,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,2,9,4,9,3,9,1001,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,2,9,4,9,3,9,1002,9,2,9,4,9,3,9,1002,9,2,9,4,9,3,9,1002,9,2,9,4,9,99';
    }
}
