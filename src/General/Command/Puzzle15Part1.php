<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use SplDoublyLinkedList;
use SplQueue;
use SplStack;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle15Part1 extends Command
{
    private const WIDTH = 22;
    private const HEIGHT = 22;

    private const NORTH = '1';
    private const SOUTH = '2';
    private const WEST = '3';
    private const EAST = '4';
    private const DIRECTION_MAP = [
        self::NORTH => 'N',
        self::SOUTH => 'S',
        self::EAST => 'E',
        self::WEST => 'W',
    ];

    private const HIT_WALL = '0';
    private const MOVED = '1';
    private const MOVED_FOUND_OYGEN = '2';

    private const WALL = '#';
    private const PATH = '.';
    private const OXYGEN_SYSTEM = 'o';


    protected static $defaultName = 'puzzle-15-part-1';
    private $grid;
    /** @var OutputInterface */
    private $output;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $row = [];
        for ($x = -self::WIDTH; $x <= self::WIDTH; $x++) {
            $row[$x] = ' ';
        }
        for ($y = -self::HEIGHT; $y <= self::HEIGHT; $y++) {
            $this->grid[$y] = $row;
        }
        $this->grid[0][0] = self::PATH;
        $position = (object) ['x' => 0, 'y' => 0];

        $computer = new RealIntCodeComputer($this->getPuzzleInput());
        $in = new SplQueue();
        $out = new SplQueue();
        $path = new SplStack();
        $path->push(clone $position);

        $callback = function(SplQueue $in, SplQueue $out) use (&$position, &$path) {
            static $lastDirection = self::NORTH;
            static $newDirection;
            static $backTracking = false;
            static $foundNewPathSinceBackTracking = false;

            $result = $out->dequeue();
            $newDirection = $this->directionToTheRight($lastDirection);

            if ($result === self::HIT_WALL) {
                $wall = $this->getPositionInDirection($position, $lastDirection);

                $this->output->writeln(sprintf('Hit a wall at % 3d,% 3d; Path: %s', $wall->x, $wall->y, $this->dumpPath(clone $path)));

                $this->grid[$wall->y][$wall->x] = self::WALL;
            }

            if ($result === self::MOVED) {
                $position = $this->getPositionInDirection($position, $lastDirection);
                ! $backTracking && $path->push(clone $position);
                $this->grid[$position->y][$position->x] = self::PATH;

                ! $backTracking && $this->output->write(sprintf('Found path at % 3d,% 3d ', $position->x, $position->y));
                ! $backTracking && $this->output->writeln('Path: ' . $this->dumpPath(clone $path));
                ! $backTracking && $foundNewPathSinceBackTracking = true;
            }

            if ($result === self::MOVED_FOUND_OYGEN) {
                $position = $this->getPositionInDirection($position, $lastDirection);
                ! $backTracking && $path->push(clone $position);

                $this->grid[$position->y][$position->x] = self::OXYGEN_SYSTEM;
                $this->output->writeln("Found oxygen system at {$position->x},{$position->y} ");
                $this->output->writeln('Path: (' . $path->count() . ')' . $this->dumpPath(clone $path));
            }


            $try = $this->getPositionInDirection($position, $newDirection);
            $attempts = 0;
            while (true) {
                if (! $this->isPositionKownInGrid($try)) {
                    if ($backTracking) {
                        $this->output->writeln('No longer tracking back. Path: ' . $this->dumpPath($path));
                        $path->push(clone $position);
                        $backTracking = false;
                    }
                    break;
                }

                $newDirection = $this->directionToTheRight($newDirection);
                $try = $this->getPositionInDirection($position, $newDirection);
                $this->output->writeln("Choosing new direction as previous direction result in a revisit, attempting {$try->x}, {$try->y}", OutputInterface::VERBOSITY_VERY_VERBOSE);
                $attempts++;

                if ($attempts > 3) {
                    if ($path->count() === 0) {
                        throw new \Exception('want to track back but no path');
                    }
                    $previousPosition = $path->pop();
                    if ($previousPosition == $position && $foundNewPathSinceBackTracking) {
                        $previousPosition = $path->pop();
                    }
                    $newDirection = $this->determineDirection($position, $previousPosition);
                    ! $backTracking && $this->output->writeln('Tracking back in path.');
                    $backTracking = true;
                    break;
                }
            }

            $dir = self::DIRECTION_MAP[$newDirection];
            $this->output->writeln(sprintf("From pos % 3d,% 3d moving $dir: ", $position->x, $position->y));
            $in->enqueue($newDirection);

            $lastDirection = $newDirection;
            $newDirection = null;
        };

        try {
            $dir = self::DIRECTION_MAP[self::NORTH];
            $this->output->writeln(sprintf("From pos % 3d,% 3d moving $dir: ", $position->x, $position->y));
            $in->enqueue(self::NORTH);
            $computer->run($output, $in, $out, $callback);
        } catch (\Exception $e) {
            $this->grid[0][0] = 's';
            foreach ($this->grid as $row) {
                $output->writeln(implode($row));
            }

            $output->writeln($e->getMessage());
        }

        return 1;
    }

    private function directionToTheRight(string $direction): string
    {
        switch ($direction) {
            case self::NORTH:
                return self::EAST;
            case self::SOUTH:
                return self::WEST;
            case self::EAST:
                return self::SOUTH;
            case self::WEST:
                return self::NORTH;
        }
    }

    private function isPositionKownInGrid(object $pos): bool
    {
        return $this->grid[$pos->y][$pos->x] !== ' ';
    }

    private function getPositionInDirection(object $pos, string $direction): object
    {
        $x = $pos->x;
        $y = $pos->y;
        switch ($direction) {
            case self::NORTH:
                $y--;
                break;
            case self::SOUTH:
                $y++;
                break;
            case self::EAST:
                $x++;
                break;
            case self::WEST:
                $x--;
                break;
        }

        return (object) ['x' => $x, 'y' => $y];
    }

    private function getPuzzleInput(): string
    {
        return '3,1033,1008,1033,1,1032,1005,1032,31,1008,1033,2,1032,1005,1032,58,1008,1033,3,1032,1005,1032,81,1008,1033,4,1032,1005,1032,104,99,101,0,1034,1039,101,0,1036,1041,1001,1035,-1,1040,1008,1038,0,1043,102,-1,1043,1032,1,1037,1032,1042,1105,1,124,101,0,1034,1039,102,1,1036,1041,1001,1035,1,1040,1008,1038,0,1043,1,1037,1038,1042,1106,0,124,1001,1034,-1,1039,1008,1036,0,1041,102,1,1035,1040,1001,1038,0,1043,1001,1037,0,1042,1106,0,124,1001,1034,1,1039,1008,1036,0,1041,1001,1035,0,1040,1001,1038,0,1043,1002,1037,1,1042,1006,1039,217,1006,1040,217,1008,1039,40,1032,1005,1032,217,1008,1040,40,1032,1005,1032,217,1008,1039,7,1032,1006,1032,165,1008,1040,5,1032,1006,1032,165,1102,1,2,1044,1105,1,224,2,1041,1043,1032,1006,1032,179,1101,0,1,1044,1105,1,224,1,1041,1043,1032,1006,1032,217,1,1042,1043,1032,1001,1032,-1,1032,1002,1032,39,1032,1,1032,1039,1032,101,-1,1032,1032,101,252,1032,211,1007,0,27,1044,1106,0,224,1102,1,0,1044,1106,0,224,1006,1044,247,101,0,1039,1034,101,0,1040,1035,102,1,1041,1036,1001,1043,0,1038,102,1,1042,1037,4,1044,1106,0,0,13,3,18,86,2,10,5,16,95,16,54,4,23,63,70,10,21,20,26,99,85,9,96,3,83,5,9,91,14,1,4,78,11,15,53,10,35,13,7,17,30,90,23,65,65,67,16,4,65,39,11,57,13,36,22,95,53,63,22,47,12,47,2,12,3,71,92,17,55,16,51,79,6,3,92,15,17,15,18,63,8,12,3,49,6,69,32,1,25,83,17,12,1,76,23,95,17,13,92,13,56,16,69,94,11,20,31,83,30,21,88,22,61,45,6,70,12,3,30,23,86,6,93,4,24,9,73,72,7,72,83,9,30,6,24,86,99,11,11,96,16,68,10,35,19,23,6,79,51,8,3,8,75,2,32,26,73,23,80,30,86,25,64,46,24,81,20,18,85,7,94,28,37,93,18,12,77,99,14,22,19,50,2,18,45,63,8,2,89,79,79,7,33,77,18,20,22,12,58,61,20,4,58,20,51,79,14,32,19,87,21,19,76,8,81,7,13,72,75,22,28,22,14,92,30,18,90,10,6,97,25,34,9,20,26,52,45,6,4,97,4,46,26,86,61,20,25,28,26,22,54,69,16,51,3,58,5,23,75,92,18,98,12,11,55,38,22,87,14,20,17,52,73,9,91,30,14,26,12,56,81,54,9,72,18,12,47,93,22,54,21,59,73,7,78,12,87,26,5,39,45,4,55,16,21,86,62,20,98,61,14,20,70,14,25,92,32,44,2,3,15,32,23,23,97,76,78,15,23,95,21,11,69,34,12,89,3,95,24,15,59,38,39,72,14,15,55,48,18,2,43,26,13,58,68,11,22,89,33,79,22,43,40,14,26,5,50,11,28,9,36,33,2,22,43,21,90,15,92,14,14,49,9,80,14,85,99,70,8,16,14,15,70,1,39,32,45,5,57,12,12,4,99,75,28,14,2,28,71,5,69,61,4,28,98,97,87,10,80,2,65,93,6,21,81,7,95,22,35,18,38,23,11,53,14,5,2,84,3,70,33,19,8,52,10,99,14,58,36,1,3,30,53,4,7,47,10,93,2,32,17,40,68,43,20,41,4,16,21,29,23,82,2,18,37,37,15,19,26,41,28,9,95,17,17,52,25,13,49,28,47,22,5,52,14,21,72,83,7,17,86,20,3,18,58,14,19,25,56,65,65,26,53,8,20,75,31,21,40,17,6,33,20,95,47,24,75,26,17,96,24,48,65,97,4,52,20,78,47,14,23,77,32,8,18,98,43,7,61,25,84,40,6,36,24,87,24,71,77,13,20,49,16,60,35,9,64,48,21,2,74,25,1,2,57,11,58,7,45,35,26,13,74,92,2,9,82,9,20,23,15,33,94,7,10,48,78,16,24,94,33,11,21,5,89,47,15,52,12,51,51,81,9,18,39,14,2,97,79,33,23,12,99,3,16,11,79,83,45,18,23,78,86,69,10,25,98,62,62,18,7,44,47,1,3,92,8,22,81,9,3,29,8,81,21,13,95,6,5,99,5,29,16,3,53,72,26,14,44,97,7,43,12,42,65,17,8,12,88,55,18,20,34,13,39,10,72,58,15,11,69,17,94,20,22,52,28,13,30,65,8,2,63,18,4,36,17,8,71,16,71,15,64,14,31,51,75,1,12,92,14,35,23,40,45,1,5,87,28,18,83,43,9,90,2,3,50,18,61,68,5,89,16,44,7,34,82,74,15,83,15,70,13,80,20,43,8,35,14,58,50,75,20,50,9,68,46,52,2,73,11,60,32,61,25,40,9,31,21,73,0,0,21,21,1,10,1,0,0,0,0,0,0';
    }

    private function determineDirection(object $position, object $previousPosition)
    {
        if ($previousPosition->y < $position->y) {
            return self::NORTH;
        }
        if ($previousPosition->y > $position->y) {
            return self::SOUTH;
        }
        if ($previousPosition->x < $position->x) {
            return self::WEST;
        }
        if ($previousPosition->x > $position->x) {
            return self::EAST;
        }
    }

    private function dumpPath(SplStack $param)
    {
        $return = [];
        foreach ($param as $item) {
            $return[] = "{$item->x},{$item->y}";
        }

        return implode(' ', array_reverse($return));
    }
}
