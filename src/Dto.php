<?php

namespace Puzzle;

final readonly class Dto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email
    ) {
    }
}
