<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle10Part2 extends Command
{
    protected static $defaultName = 'puzzle-10-part-2';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 1;
    }

    private function getPuzzleInput(): string
    {
        return '';
    }
}
