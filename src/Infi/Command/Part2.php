<?php declare(strict_types=1);

namespace AoC\Infi\Command;

use AoC\Infi\Model\Flat;
use AoC\Infi\Model\Jump;
use AoC\Infi\Reader\FileReader;
use Doctrine\Common\Collections\ArrayCollection;
use Fhaculty\Graph\Edge\Directed;
use Fhaculty\Graph\Graph;
use Graphp\Algorithms\ShortestPath\Dijkstra;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Part2 extends Command
{
    protected static $defaultName = 'part-2';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $route = FileReader::execute('https://aoc-2019-backend.azurewebsites.net/generate_level/TtsVC1bm9e');

        $graph = new Graph();
        $vertexes = new ArrayCollection();

        /** @var Flat $flat */
        foreach ($route->getFlats() as $flat) {
            $vertexes->offsetSet($flat->getPosition(), $graph->createVertex($flat->getPosition()));
        }
        /** @var Flat $flat */
        foreach ($route->getFlats() as $flat) {
            /** @var Jump $jump */
            foreach ($route->getFlats()->getPossibleJumps($flat) as $jump) {
                $e = $vertexes->offsetGet($flat->getPosition())->createEdgeTo($vertexes->offsetGet($jump->getTo()->getPosition()));
                $e->setWeight($jump->getRequiredEnergy());
            }
        }

        $alg = new Dijkstra($vertexes->first());
        $path = $alg->getWalkTo($vertexes->last());

        $jumps = [];
        $energy = 0;
        /** @var Directed $edge */
        foreach ($path->getEdges() as $edge) {
            $jumps[] = $route->getFlats()->calculateJump(
                $route->getFlats()->getFlatAtPosition($edge->getVertexStart()->getId()),
                $route->getFlats()->getFlatAtPosition($edge->getVertexEnd()->getId())
            );
            $energy += $jump->getRequiredEnergy();
        }


        $output->writeln(json_encode($jumps));
        $output->writeln("The required energy is $energy");

        return 1;
    }
}
