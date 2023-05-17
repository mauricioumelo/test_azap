<?php

namespace App\UseCase\DTO\Senders;

class ListSendersInputDto
{
    public function __construct(
        public string $order = 'desc',
        public int $page = 1,
        public int $limit = 15
    ) {
    }
}
