<?php

namespace App\Domain\Enum\Invoice;

enum Status: string
{
    case COMPROVADO = 'COMPROVADO';
    case ABERTO = 'ABERTO';
}
