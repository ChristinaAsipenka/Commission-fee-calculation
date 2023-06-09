<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Parser\CSVParser;
use App\Service\ParserService;
use PHPUnit\Framework\TestCase;

class ParserServiceTest extends TestCase
{
    public function testParse()
    {
        $filename = realpath(__DIR__ . '/../txt/operations.csv');

        $parserStub = $this->createStub(CSVParser::class);
        $parserStub->method('parse')
            ->willReturn($this->generateData());

        $parserService = new ParserService($parserStub);

        $result = $parserService->parse($filename);
        $this->assertInstanceOf(\Generator::class, $result);
    }

    private function generateData(): \Generator
    {
        yield 'data';
    }
}

