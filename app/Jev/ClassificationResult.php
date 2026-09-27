<?php

namespace App\Jev;

final readonly class ClassificationResult
{
    public function __construct(
        public ?float $probability,
        public ?string $error,
    ) {}

    public function succeeded(): bool
    {
        return $this->error === null && $this->probability !== null;
    }
}
