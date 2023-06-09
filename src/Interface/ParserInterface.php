<?php

declare(strict_types=1);

namespace App\Interface;

interface ParserInterface
{
    public function mimeType(): string;

    public function parse(string $filepath): iterable;
}
