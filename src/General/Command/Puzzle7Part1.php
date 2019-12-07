<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle7Part1 extends Command
{
    protected static $defaultName = 'puzzle-7-part-1';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $max = 0;
        $sequence = '';
        for ($p1 = 0; $p1 < 5; $p1++) {
            for ($p2 = 0; $p2 < 5; $p2++) {
                for ($p3 = 0; $p3 < 5; $p3++) {
                    for ($p4 = 0; $p4 < 5; $p4++) {
                        for ($p5 = 0; $p5 < 5; $p5++) {
                            if (count(array_unique([$p1, $p2, $p3, $p4, $p5])) !== 5) {
                                continue;
                            }

                            $output->writeln("Phase setting sequence $p1,$p2,$p3,$p4,$p5");
                            $inp = 0;
                            foreach ([$p1, $p2, $p3, $p4, $p5] as $phaseSetting) {
                                $computer = new RealIntCodeComputer($this->getPuzzleInput());
                                $in = new \SplQueue();
                                $in->enqueue($phaseSetting);
                                $in->enqueue($inp);
                                $out = new \SplQueue();

                                $computer->run($output, $in, $out);
                                $inp = $out->dequeue();
                            }

                            if ($inp > $max) {
                                $output->writeln('Found new max ' . $max);
                                $max = $inp;
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
//        return '3,15,3,16,1002,16,10,16,1,16,15,15,4,15,99,0,0';
//        return '3,23,3,24,1002,24,10,24,1002,23,-1,23,101,5,23,23,1,24,23,23,4,23,99,0,0';
//        return '3,31,3,32,1002,32,10,32,1001,31,-2,31,1007,31,0,33,1002,33,7,33,1,33,31,31,1,32,31,31,4,31,99,0,0,0';
        return '3,8,1001,8,10,8,105,1,0,0,21,38,47,64,89,110,191,272,353,434,99999,3,9,101,4,9,9,102,3,9,9,101,5,9,9,4,9,99,3,9,1002,9,5,9,4,9,99,3,9,101,2,9,9,102,5,9,9,1001,9,5,9,4,9,99,3,9,1001,9,5,9,102,4,9,9,1001,9,5,9,1002,9,2,9,1001,9,3,9,4,9,99,3,9,102,2,9,9,101,4,9,9,1002,9,4,9,1001,9,4,9,4,9,99,3,9,101,1,9,9,4,9,3,9,101,1,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,101,2,9,9,4,9,3,9,101,1,9,9,4,9,3,9,1001,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,101,2,9,9,4,9,99,3,9,101,2,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,101,2,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,101,2,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,101,2,9,9,4,9,3,9,1001,9,2,9,4,9,3,9,102,2,9,9,4,9,99,3,9,1001,9,2,9,4,9,3,9,1001,9,2,9,4,9,3,9,101,1,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,1001,9,1,9,4,9,3,9,1002,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,1002,9,2,9,4,9,3,9,101,1,9,9,4,9,3,9,101,1,9,9,4,9,99,3,9,102,2,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,1001,9,1,9,4,9,3,9,1002,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,1001,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,1,9,4,9,3,9,1002,9,2,9,4,9,99,3,9,101,1,9,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,2,9,4,9,3,9,1001,9,2,9,4,9,3,9,102,2,9,9,4,9,3,9,102,2,9,9,4,9,3,9,1001,9,2,9,4,9,3,9,1002,9,2,9,4,9,3,9,1002,9,2,9,4,9,3,9,1002,9,2,9,4,9,99';
    }
}
