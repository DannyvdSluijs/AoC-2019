<?php declare(strict_types=1);

namespace AoC\General\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle12Part1 extends Command
{
    protected static $defaultName = 'puzzle-12-part-1';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $puzzleInput = $this->getPuzzleInput();
        $planets = [];
        foreach (explode("\n", $puzzleInput) as $line) {
            sscanf($line, '<x=%d, y=%d, z=%d>', $x, $y, $z);
            $planets[] = (object) ['x' => $x, 'y' => $y, 'z' => $z, 'vX' => 0, 'vY' => 0, 'vZ' => 0];
        }
        unset($x, $y, $z, $line, $puzzleInput);

        $output->writeln('After 0 step:');
        foreach ($planets as $planet) {
            $output->writeln(sprintf('pos=<x=% 3d, y=% 3d, z=% 3d>, vel=<x=  0, y=  0, z=  0>', $planet->x, $planet->y, $planet->z));
        }

        for ($l = 1; $l <= 1000; $l++) {
            /* Gravity */
            foreach ($planets as $planet) {
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

            $output->writeln("After $l steps:");
            foreach ($planets as $planet) {
                $output->writeln(sprintf('pos=<x=% 3d, y=% 3d, z=% 3d>, vel=<x=% 3d, y=% 3d, z=% 3d>', $planet->x, $planet->y, $planet->z, $planet->vX, $planet->vY, $planet->vZ));
            }
        }

        $totalEnergy = 0;
        foreach ($planets as $planet) {
            $pot = abs($planet->x) + abs($planet->y) + abs($planet->z);
            $kin = abs($planet->vX) + abs($planet->vY) + abs($planet->vZ);

            $totalEnergy += $pot * $kin;
        }

        $output->writeln('Total energy in the system: ' . $totalEnergy);
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
