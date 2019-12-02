<?php declare(strict_types=1);

namespace AoC\Infi\Command;

use AoC\Infi\Model\Jump;
use AoC\Infi\Reader\FileReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Part1 extends Command
{
    protected static $defaultName = 'part-1';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $route = FileReader::execute('https://aoc-2019-backend.azurewebsites.net/generate_level/TtsVC1bm9e');

        $santaPosition = $route->getFlats()->first()->getPosition();
        $santaHeight = $route->getFlats()->first()->getHeight();

        $steps = 0;

        /** @var Jump $jump */
        foreach ($route->getJumps() as $jump) {
            $steps++;
            $output->writeln("Step $steps", OutputInterface::VERBOSITY_VERBOSE);
            $nextPosition = $santaPosition + $jump->getRight() + 1;
            $nextHeight = $santaHeight + $jump->getUp();

            /* Validate if there is a flat a new horizontal position */
            if (! $route->getFlats()->hasFlatAtPosition($nextPosition)) {
                $output->writeln("At step $steps santa fell to his death as there is no flat at $nextPosition");
                return 0;
            }
            $output->writeln("Jumping from flat at $santaPosition to $nextPosition", OutputInterface::VERBOSITY_VERBOSE);


            /* Validate if there is a flat at the position the height of the flat matches */
            $nextFlat = $route->getFlats()->getFlatAtPosition($nextPosition);
            if ($nextFlat->getHeight() > $nextHeight) {
                $output->writeln("At step $steps santa fell to his death as the flat [{$nextFlat}] is higher his height: $nextHeight");
                return 0;
            }

            /* Correct for jumping to lower flats */
            $nextHeight = $nextFlat->getHeight();

            $output->writeln("Jumping from height $santaHeight to $nextHeight", OutputInterface::VERBOSITY_VERBOSE);

            $santaPosition = $nextPosition;
            $santaHeight = $nextHeight;
        }

        $output->writeln('Santa made it to the last appartment without falling down.');
        return 1;
    }
}
