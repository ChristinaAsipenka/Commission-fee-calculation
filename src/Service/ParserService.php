<?php

declare(strict_types=1);

namespace App\Service;

use App\Interface\ParserInterface;
use App\Service\Parser\CSVParser;

class ParserService
{
    private CSVParser $parser;

    public function __construct(CSVParser $parser)
    {
        /** @var ParserInterface $parser */
        $this->parser = $parser;
    }

    public function parse(string $filename): iterable
    {
        return $this->parser->parse($filename);
    }
}
