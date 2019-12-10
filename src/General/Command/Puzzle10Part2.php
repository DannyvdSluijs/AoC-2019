<?php declare(strict_types=1);

namespace AoC\General\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle10Part2 extends Command
{
    protected static $defaultName = 'puzzle-10-part-2';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $asteroidMap = $this->getPuzzleInput();
        $asteroids = [];
        $lineOfSight = [];

        foreach (explode("\n", $asteroidMap) as $y => $line) {
            foreach (str_split($line) as $x => $item) {
                if ($item !== '#') {
                    continue;
                }
                $asteroids[] = (object) ['x' => $x, 'y' => $y];
            }
        }

        $laser = (object) ['x' => 37, 'y' => 25]; /* Answer part 1 */

        foreach ($asteroids as $asteroid) {
            if ($laser->x === $asteroid->x && $laser->y === $asteroid->y) {
                continue;
            }

            $xDiff = $asteroid->x - $laser->x;
            $yDiff = $laser->y - $asteroid->y;

            $angle = (rad2deg(atan2($xDiff, $yDiff)) + 360) * 1000 % 360000;
            $output->writeln($angle);

            if (! array_key_exists($angle, $lineOfSight)) {
                $lineOfSight[$angle] = [];
            }

            $distance = sqrt((abs($xDiff) ** 2) + (abs($yDiff) ** 2));
            $lineOfSight[$angle][$distance] = $asteroid;
            ksort($lineOfSight[$angle]);
        }

        ksort($lineOfSight);

        $hits = 0;
        while (count($lineOfSight) > 0) {
            foreach ($lineOfSight as $k => $angle) {
                $asteroid = array_shift($angle);
                $lineOfSight[$k] = $angle;
                $hits++;
                $output->writeln("Hit $hits asteroid (x: {$asteroid->x}; y: {$asteroid->y}; a:$k)");

                if (count($angle) === 0) {
                    unset($lineOfSight[$k]);
                }
            }
        }

        return 1;
    }

    private function getPuzzleInput(): string
    {
        return '#.#................#..............#......#......
.......##..#..#....#.#.....##...#.........#.#...
.#...............#....#.##......................
......#..####.........#....#.......#..#.....#...
.....#............#......#................#.#...
....##...#.#.#.#.............#..#.#.......#.....
..#.#.........#....#..#.#.........####..........
....#...#.#...####..#..#..#.....#...............
.............#......#..........#...........#....
......#.#.........#...............#.............
..#......#..#.....##...##.....#....#.#......#...
...#.......##.........#.#..#......#........#.#..
#.............#..........#....#.#.....#.........
#......#.#................#.......#..#.#........
#..#.#.....#.....###..#.................#..#....
...............................#..........#.....
###.#.....#.....#.............#.......#....#....
.#.....#.........#.....#....#...................
........#....................#..#...............
.....#...#.##......#............#......#.....#..
..#..#..............#..#..#.##........#.........
..#.#...#.......#....##...#........#...#.#....#.
.....#.#..####...........#.##....#....#......#..
.....#..#..##...............................#...
.#....#..#......#.#............#........##...#..
.......#.....................#..#....#.....#....
#......#..###...........#.#....#......#.........
..............#..#.#...#.......#..#.#...#......#
.......#...........#.....#...#.............#.#..
..##..##.............#........#........#........
......#.............##..#.........#...#.#.#.....
#........#.........#...#.....#................#.
...#.#...........#.....#.........#......##......
..#..#...........#..........#...................
.........#..#.......................#.#.........
......#.#.#.....#...........#...............#...
......#.##...........#....#............#........
#...........##.#.#........##...........##.......
......#....#..#.......#.....#.#.......#.##......
.#....#......#..............#.......#...........
......##.#..........#..................#........
......##.##...#..#........#............#........
..#.....#.................###...#.....###.#..#..
....##...............#....#..................#..
.....#................#.#.#.......#..........#..
#........................#.##..........#....##..
.#.........#.#.#...#...#....#........#..#.......
...#..#.#......................#...............#';
    }
}
