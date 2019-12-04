<?php declare(strict_types=1);

namespace AoC\General\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle4Part1 extends Command
{
    protected static $defaultName = 'puzzle-4-part-1';

    private $answers = 0;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($p1 = 1; $p1 < 6; $p1++) {
            for ($p2 = $p1; $p2 <= 9; $p2++) {
                for ($p3 = $p2; $p3 <= 9; $p3++) {
                    for ($p4 = $p3; $p4 <= 9; $p4++) {
                        for ($p5 = $p4; $p5 <= 9; $p5++) {
                            for ($p6 = $p5; $p6 <= 9; $p6++) {
                                $answer = "$p1$p2$p3$p4$p5$p6";

                                if ((int) $answer < 123257) {
                                    continue;
                                }

                                if (self::isValid($answer)) {
                                    $this->answers++;
                                }
                            }
                        }
                    }
                }
            }
        }

        $output->writeln("There are {$this->answers} possible passwords");
        return 1;
    }

    public static function isValid(string $answer): bool
    {
        $chars = count_chars($answer);
        if (count($chars) === 6) {
            return false;
        }

        $filtered = array_filter($chars, static function ($count) {return $count >= 2;});

        if (empty($filtered)) {
            return false;
        }

        return true;
    }
}
