<?php declare(strict_types=1);

namespace AoC\General\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle12Part2 extends Command
{
    protected static $defaultName = 'puzzle-12-part-2';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $seen = ['x' => [], 'y' => [], 'z' => []];
        $resolved = [];

        $puzzleInput = $this->getPuzzleInput();
        $planets = [];
        foreach (explode("\n", $puzzleInput) as $line) {
            sscanf($line, '<x=%d, y=%d, z=%d>', $x, $y, $z);
            $planets[] = (object) ['x' => $x, 'y' => $y, 'z' => $z, 'vX' => 0, 'vY' => 0, 'vZ' => 0];
        }
        unset($x, $y, $z, $line, $puzzleInput);

        $keys = ['x' => '', 'y' => '', 'z' => ''];
        foreach ($planets as $k => $planet) {
            foreach (['x', 'y', 'z'] as $axis) {
                $v = 'v' . ucfirst($axis);
                $keys[$axis] = $keys[$axis] . $planet->$axis . '/' . $planet->$v . '|';
            }
        }

        $l = 0;
        while(count($resolved) < 3) {
            $l++;
            if ($l % 10000000 == 0) {
                $output->writeln("Step $l");
            }
            /* Gravity */
            foreach ($planets as $k => $planet) {
                foreach ($planets as $other) {
                    $planet->vX += $planet->x === $other->x ? 0 : ($other->x > $planet->x ? 1 : -1);
                    $planet->vY += $planet->y === $other->y ? 0 : ($other->y > $planet->y ? 1 : -1);
                    $planet->vZ += $planet->z === $other->z ? 0 : ($other->z > $planet->z ? 1 : -1);
                }
            }

            /* Velocity */
            foreach ($planets as $planet) {
                $planet->x += $planet->vX;
                $planet->y += $planet->vY;
                $planet->z += $planet->vZ;
            }

            $keys = ['x' => '', 'y' => '', 'z' => ''];
            foreach ($planets as $k => $planet) {
                foreach (['x', 'y', 'z'] as $axis) {
                    $v = 'v' . ucfirst($axis);
                    $keys[$axis] = $keys[$axis] . $planet->$axis . '/' . $planet->$v . '|';
                }
            }

            foreach (['x', 'y', 'z'] as $axis) {
                if(array_key_exists($keys[$axis], $seen[$axis]) && ! array_key_exists($axis, $resolved)) {
                    $output->writeln("After $l steps the planets are back on a previous state for the $axis axis");
                    $resolved[$axis] = $l - 1;
                }
                $seen[$axis][$keys[$axis]] = true;
            }
        }

        $gcd = gmp_gcd((string) $resolved['x'], (string) $resolved['y']);
        $div = (int) gmp_strval($gcd);
        $ans = $resolved['x'] * $resolved['y'] / $div;

        $gcd = gmp_gcd((string) $ans, (string) $resolved['z']);
        $div = (int) gmp_strval($gcd);
        $ans = $ans * $resolved['z'] / $div;

        $output->writeln("After $ans steps the universe is back on it feet");

        return 1;
    }

    private function getPuzzleInput(): string
    {
        return '<x=1, y=4, z=4>
<x=-4, y=-1, z=19>
<x=-15, y=-14, z=12>
<x=-17, y=1, z=10>';
    }
}
