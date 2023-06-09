<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\Interface\ParserInterface;
use Symfony\Component\String\Exception\RuntimeException;

class CSVParser implements ParserInterface
{
    public function mimeType(): string
    {
        return 'text/csv';
    }

    public function parse(string $filepath): iterable
    {
        try {
            $file = fopen($filepath, 'r');

            while ($line = fgetcsv($file)) {
                yield $line;
            }
        } catch (\Throwable $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
