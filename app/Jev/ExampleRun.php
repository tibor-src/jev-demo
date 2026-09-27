<?php

namespace App\Jev;

final readonly class ExampleRun
{
    /**
     * @param  array<string, mixed>|null  $data
     */
    public function __construct(
        public ?array $data,
        public ?string $error,
    ) {}
}
