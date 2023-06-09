<?php

declare(strict_types=1);

namespace App\Tests\Service\Parser;

use App\Service\Parser\CSVParser;
use PHPUnit\Framework\TestCase;

class CSVParserTest extends TestCase
{
    public function testMimeType(): void
    {
        $csvParser = new CSVParser();

        $result = $csvParser->mimeType();

        $this->assertSame('text/csv', $result);
    }

    public function testParse(): void
    {
        $csvParser = new CSVParser();

        $filepath = realpath(__DIR__ . '/../../txt/operations.csv');

        $expected = [
            ['2014-12-31','4','private','withdraw','1200.00','EUR'],
            ['2015-01-01','4','private','withdraw','1000.00','EUR'],
            ['2016-01-05','4','private','withdraw','1000.00','EUR']
        ];

        $result = iterator_to_array($csvParser->parse($filepath));

        $this->assertSame($expected, $result);
    }

    public function testParseException(): void
    {
        $csvParser = new CSVParser();

        $filepath = 'path/to/nonexistent-file.csv';

        $this->expectException(\Symfony\Component\String\Exception\RuntimeException::class);

        iterator_to_array($csvParser->parse($filepath));
    }
}
