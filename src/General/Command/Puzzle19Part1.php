<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use SplDoublyLinkedList;
use SplQueue;
use SplStack;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle19Part1 extends Command
{
    protected static $defaultName = 'puzzle-19-part-1';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $in = new SplQueue();
        $out = new SplQueue();
        $beamCount = 0;

        $callback = function(SplQueue $in, SplQueue $out) use ($output, &$beamCount) {
            static $count = 0;

            $param = $out->dequeue();
            $beam = $param === '0' ? '.' : '#';

            if ($beam === '#') {
                $beamCount++;
            }

            $output->write($beam);
            $count++;

            if ($count > 50) {
                $output->writeln('');
                $count = 0;
            }
        };

        $computer = new RealIntCodeComputer($this->getPuzzleInput());

        for ($y = 0; $y < 50; $y++) {
            for ($x = 0; $x < 50; $x++) {
                $in->enqueue((string) $x);
                $in->enqueue((string) $y);
                $computer = new RealIntCodeComputer($this->getPuzzleInput());
                $computer->run($output, $in, $out, $callback);
            }
        }

        $output->writeln('Beam count: ' . $beamCount);

        return 1;
    }

    private function getPuzzleInput(): string
    {
        return '109,424,203,1,21102,1,11,0,1106,0,282,21101,0,18,0,1105,1,259,1201,1,0,221,203,1,21102,31,1,0,1105,1,282,21101,38,0,0,1106,0,259,20101,0,23,2,22102,1,1,3,21101,0,1,1,21101,0,57,0,1106,0,303,2101,0,1,222,21001,221,0,3,20102,1,221,2,21102,1,259,1,21102,1,80,0,1106,0,225,21101,33,0,2,21102,1,91,0,1106,0,303,1201,1,0,223,21002,222,1,4,21101,259,0,3,21101,0,225,2,21101,225,0,1,21101,0,118,0,1106,0,225,20101,0,222,3,21102,1,102,2,21102,133,1,0,1105,1,303,21202,1,-1,1,22001,223,1,1,21101,148,0,0,1106,0,259,2101,0,1,223,21001,221,0,4,21002,222,1,3,21101,0,15,2,1001,132,-2,224,1002,224,2,224,1001,224,3,224,1002,132,-1,132,1,224,132,224,21001,224,1,1,21102,195,1,0,106,0,108,20207,1,223,2,21001,23,0,1,21102,1,-1,3,21101,0,214,0,1105,1,303,22101,1,1,1,204,1,99,0,0,0,0,109,5,2102,1,-4,249,22101,0,-3,1,22101,0,-2,2,21202,-1,1,3,21101,250,0,0,1105,1,225,22102,1,1,-4,109,-5,2106,0,0,109,3,22107,0,-2,-1,21202,-1,2,-1,21201,-1,-1,-1,22202,-1,-2,-2,109,-3,2105,1,0,109,3,21207,-2,0,-1,1206,-1,294,104,0,99,22101,0,-2,-2,109,-3,2106,0,0,109,5,22207,-3,-4,-1,1206,-1,346,22201,-4,-3,-4,21202,-3,-1,-1,22201,-4,-1,2,21202,2,-1,-1,22201,-4,-1,1,22101,0,-2,3,21102,1,343,0,1106,0,303,1106,0,415,22207,-2,-3,-1,1206,-1,387,22201,-3,-2,-3,21202,-2,-1,-1,22201,-3,-1,3,21202,3,-1,-1,22201,-3,-1,2,22102,1,-4,1,21102,384,1,0,1106,0,303,1106,0,415,21202,-4,-1,-4,22201,-4,-3,-4,22202,-3,-2,-2,22202,-2,-4,-4,22202,-3,-2,-3,21202,-4,-1,-2,22201,-3,-2,1,21202,1,1,-4,109,-5,2106,0,0';
    }
}
