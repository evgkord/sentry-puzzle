<?php

namespace Puzzle;

use Psr\Log\LoggerInterface;

final readonly class Service
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function buildDto(): ?Dto
    {
        try {
            $data = [];

            return new Dto(
                id: (int)$data['id'],
                name: (string)$data['name'],
                email: (string)$data['email']
            );
        } catch (Throwable $e) {
            $this->logger->error('Unable build Dto');
        }

        return null;
    }
}
