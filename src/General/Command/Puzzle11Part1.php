<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle11Part1 extends Command
{
    protected static $defaultName = 'puzzle-11-part-1';

    private const WHITE = '1';
    private const BLACK = '0';
    private const SIZE = 60;

    private $grid = [];
    private $uniquePaintedPanels = [];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $row = [];
        for ($x = -self::SIZE; $x <= self::SIZE; $x++) {
            $row[$x] = self::BLACK;
        }
        for ($x = -self::SIZE; $x <= self::SIZE; $x++) {
            $this->grid[$x] = $row;
        }

        $robotLocation = (object) ['x' => 0, 'y' => 0, 'h' => 'N'];

        $computer = new RealIntCodeComputer($this->getPuzzleInput());
        $in = new \SplQueue();
        $out = new \SplQueue();

        $in->enqueue($this->getColorFromGrid($robotLocation));

        $callback = function() use ($in, $out, &$robotLocation) {
            static $count = 0;
            $count ++;

            /* Un-even: Paint instruction */
            if ($count % 2 === 1) {
                $colour = $out->dequeue();
                $this->paintPanel($robotLocation, $colour);
                return;
            }
            /* Even: Turn & move and provide current color */
            $turn = $out->dequeue();
            switch ($robotLocation->h) {
                case 'N':
                    $robotLocation->h = ($turn == 0) ? 'W' : 'E';
                    $robotLocation->x = ($turn == 0) ? $robotLocation->x - 1 : $robotLocation->x + 1;
                    break;
                case 'E':
                    $robotLocation->h = ($turn == 0) ? 'N' : 'S';
                    $robotLocation->y = ($turn == 0) ? $robotLocation->y - 1 : $robotLocation->y + 1;
                    break;
                case 'S':
                    $robotLocation->h = ($turn == 0) ? 'E' : 'W';
                    $robotLocation->x = ($turn == 0) ? $robotLocation->x + 1 : $robotLocation->x - 1;
                    break;
                case 'W':
                    $robotLocation->h = ($turn == 0) ? 'S' : 'N';
                    $robotLocation->y = ($turn == 0) ? $robotLocation->y + 1 : $robotLocation->y - 1;
                    break;
            }
            $in->enqueue($this->getColorFromGrid($robotLocation));
        };

        $computer->run($output, $in, $out, $callback);

        $count = count($this->uniquePaintedPanels);
        $output->writeln("$count uniquely painted panels");

        return 1;
    }

    private function getColorFromGrid($location): string
    {
        return $this->grid[$location->y][$location->x] === self::BLACK ? self::BLACK : self::WHITE;
    }

    private function paintPanel($location, $color): void
    {
        /* keep track of unique panels that have been painted */
        $this->grid[$location->y][$location->x] = $color;

        $key = $location->y * 100 + $location->x;
        if (! array_key_exists($key, $this->uniquePaintedPanels)) {
            $this->uniquePaintedPanels[$key] = $color;
        }
    }

    private function getPuzzleInput(): string
    {
        return '3,8,1005,8,325,1106,0,11,0,0,0,104,1,104,0,3,8,102,-1,8,10,1001,10,1,10,4,10,108,0,8,10,4,10,101,0,8,28,2,3,7,10,2,1109,3,10,2,102,0,10,2,1005,12,10,3,8,102,-1,8,10,101,1,10,10,4,10,1008,8,0,10,4,10,101,0,8,67,2,109,12,10,1,1003,15,10,3,8,1002,8,-1,10,1001,10,1,10,4,10,108,1,8,10,4,10,101,0,8,96,3,8,102,-1,8,10,101,1,10,10,4,10,1008,8,0,10,4,10,1002,8,1,119,3,8,102,-1,8,10,1001,10,1,10,4,10,1008,8,0,10,4,10,101,0,8,141,3,8,1002,8,-1,10,101,1,10,10,4,10,108,0,8,10,4,10,1001,8,0,162,1,106,17,10,1006,0,52,1006,0,73,3,8,102,-1,8,10,1001,10,1,10,4,10,108,1,8,10,4,10,1001,8,0,194,1006,0,97,1,1004,6,10,1006,0,32,2,8,20,10,3,8,102,-1,8,10,101,1,10,10,4,10,1008,8,1,10,4,10,102,1,8,231,1,1,15,10,1006,0,21,1,6,17,10,2,1005,8,10,3,8,102,-1,8,10,101,1,10,10,4,10,108,1,8,10,4,10,102,1,8,267,2,1007,10,10,3,8,1002,8,-1,10,1001,10,1,10,4,10,1008,8,1,10,4,10,102,1,8,294,1006,0,74,2,1003,2,10,1,107,1,10,101,1,9,9,1007,9,1042,10,1005,10,15,99,109,647,104,0,104,1,21101,936333018008,0,1,21101,342,0,0,1106,0,446,21102,937121129228,1,1,21101,0,353,0,1105,1,446,3,10,104,0,104,1,3,10,104,0,104,0,3,10,104,0,104,1,3,10,104,0,104,1,3,10,104,0,104,0,3,10,104,0,104,1,21101,0,209383001255,1,21102,400,1,0,1106,0,446,21101,0,28994371675,1,21101,411,0,0,1105,1,446,3,10,104,0,104,0,3,10,104,0,104,0,21101,867961824000,0,1,21101,0,434,0,1106,0,446,21102,1,983925674344,1,21101,0,445,0,1106,0,446,99,109,2,21201,-1,0,1,21102,40,1,2,21101,477,0,3,21102,467,1,0,1106,0,510,109,-2,2106,0,0,0,1,0,0,1,109,2,3,10,204,-1,1001,472,473,488,4,0,1001,472,1,472,108,4,472,10,1006,10,504,1101,0,0,472,109,-2,2106,0,0,0,109,4,1201,-1,0,509,1207,-3,0,10,1006,10,527,21102,1,0,-3,21202,-3,1,1,21201,-2,0,2,21102,1,1,3,21102,1,546,0,1106,0,551,109,-4,2105,1,0,109,5,1207,-3,1,10,1006,10,574,2207,-4,-2,10,1006,10,574,22101,0,-4,-4,1105,1,642,21202,-4,1,1,21201,-3,-1,2,21202,-2,2,3,21101,0,593,0,1105,1,551,22102,1,1,-4,21101,1,0,-1,2207,-4,-2,10,1006,10,612,21102,1,0,-1,22202,-2,-1,-2,2107,0,-3,10,1006,10,634,21201,-1,0,1,21101,634,0,0,105,1,509,21202,-2,-1,-2,22201,-4,-2,-4,109,-5,2106,0,0';
    }
}
