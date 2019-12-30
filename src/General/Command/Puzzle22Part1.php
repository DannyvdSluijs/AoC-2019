<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use SplDoublyLinkedList;
use SplQueue;
use SplStack;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle22Part1 extends Command
{
    protected static $defaultName = 'puzzle-22-part-1';

    private static $deckSize = 10007;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $deck = range(0, self::$deckSize - 1);
        $instructions = explode("\n", $this->getPuzzleInput());

        foreach ($instructions as $position => $instruction) {
            $output->writeln(implode(' ', $deck));
            $output->writeln($instruction);

            $lastSpacePosition = strrpos($instruction, ' ');
            if ($lastSpacePosition === false) {
                throw new \Exception("No space found in '$instruction'");
            }

            $operation = substr($instruction, 0, $lastSpacePosition);
            $amount = (int) substr($instruction, $lastSpacePosition);

            switch ($operation) {
                case 'deal with increment':
                    $newDeck = $deck;
                    foreach ($deck as $cardPosition => $card) {
                        $newPosition = ($cardPosition * $amount) % self::$deckSize;
                        $newDeck[$newPosition] = $card;
                    }

                    $uniques = array_unique($newDeck);
                    $uniqueCount = count($uniques);
                    if ($uniqueCount !== self::$deckSize) {
                        throw new \Exception("After $instruction the deck only contains $uniqueCount unique cards");
                    }
                    $deck = $newDeck;
                    break;
                case 'deal into new': /* Actually deal into new stack but since we are splitting on the last space. */
                    $deck = array_reverse($deck);
                    break;
                case 'cut':
                    $deck = array_merge(array_slice($deck, $amount), array_slice($deck, 0, $amount));
                    break;
                default:
                    throw new \Exception("No match for case '$operation'");
            }
        }

        $output->writeln('Final deck:');
        $output->writeln(implode(' ', $deck));
        $card2019Position = array_search(2019, $deck);
        $output->writeln("Card 2019 can be found at position $card2019Position");
        /* 7726 is too high */

        return 1;
    }


    private function getPuzzleInput(): string
    {
        return 'deal with increment 74
deal into new stack
deal with increment 67
cut 6315
deal with increment 15
cut -8049
deal with increment 69
cut 2275
deal with increment 25
cut 4811
deal with increment 47
cut -9792
deal with increment 26
cut -3014
deal with increment 47
cut -1093
deal with increment 39
cut -5322
deal with increment 14
cut -7375
deal with increment 16
cut 9627
deal into new stack
cut 1632
deal into new stack
cut -2904
deal with increment 69
cut -3328
deal with increment 60
cut 7795
deal into new stack
deal with increment 37
cut -4238
deal with increment 19
cut -3170
deal with increment 45
cut 8631
deal with increment 64
cut -2380
deal with increment 59
cut -2802
deal with increment 19
cut -3369
deal with increment 45
deal into new stack
deal with increment 71
cut 5452
deal with increment 73
cut -6609
deal with increment 33
cut 1892
deal with increment 5
cut 1395
deal into new stack
cut -8514
deal with increment 46
deal into new stack
deal with increment 15
cut 3963
deal with increment 2
cut -2965
deal into new stack
cut 640
deal with increment 13
cut 8889
deal with increment 62
cut 8331
deal with increment 49
cut 6169
deal with increment 71
deal into new stack
deal with increment 33
cut 6342
deal with increment 52
cut 2875
deal with increment 39
cut 4283
deal with increment 19
cut 4102
deal with increment 57
deal into new stack
cut -7801
deal with increment 38
cut 4273
deal with increment 58
cut -2971
deal with increment 46
deal into new stack
cut 8043
deal with increment 52
cut -7108
deal with increment 21
cut 507
deal with increment 70
cut -8658
deal with increment 64
cut 7213
deal into new stack
deal with increment 61
cut 9439';
    }
}
