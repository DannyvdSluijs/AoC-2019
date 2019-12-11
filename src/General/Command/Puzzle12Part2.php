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
        return 1;
    }

    private function getPuzzleInput(): string
    {
        return '';
    }
}
