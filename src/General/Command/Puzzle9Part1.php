<?php declare(strict_types=1);

namespace AoC\General\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle9Part1 extends Command
{
    protected static $defaultName = 'puzzle-9-part-1';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        return 1;
    }

    private function getPuzzleInput(): string
    {
        return '';
    }
}
