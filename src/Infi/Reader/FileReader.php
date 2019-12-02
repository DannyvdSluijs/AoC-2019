<?php declare(strict_types=1);

namespace AoC\Infi\Reader;

use AoC\Infi\Model\Flat;
use AoC\Infi\Model\Flats;
use AoC\Infi\Model\Jump;
use AoC\Infi\Model\Jumps;
use AoC\Infi\Model\Route;

class FileReader
{
    public static function execute(string $filePath): \AoC\Infi\Model\Route
    {
        $jsonString = file_get_contents($filePath);
        $jsonObject = json_decode($jsonString, false, 512, JSON_THROW_ON_ERROR);

        $flats = [];
        $jumps = [];
        foreach ($jsonObject->flats as $flat) {
            $flats[] = new Flat($flat[0], $flat[1]);
        }
        foreach ($jsonObject->sprongen as $jump) {
            $jumps[] = new Jump($jump[0], $jump[1]);
        }

        return new Route(new Flats(...$flats), new Jumps(...$jumps));
    }
}
