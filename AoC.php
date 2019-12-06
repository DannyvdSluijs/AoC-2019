#!/usr/bin/env php
<?php declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use \AoC\General\Command;

$application = new Application();

$application->add(new Command\Puzzle1());
$application->add(new Command\Puzzle2());
$application->add(new Command\Puzzle3());
$application->add(new Command\Puzzle4Part1());
$application->add(new Command\Puzzle4Part2());
$application->add(new Command\Puzzle5Part1());
$application->add(new Command\Puzzle5Part2());
$application->add(new Command\Puzzle6Part1());
$application->add(new Command\Puzzle6Part2());

$application->run();