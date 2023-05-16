<?php

namespace App\Domain\Entity\Invoices;

use App\Domain\Entity\Traits\MagicMethodsTrait;
use App\Domain\Enum\Invoice\Status;
use DateInterval;
use DateTime;

class Invoice
{
    use MagicMethodsTrait;

    public function __construct(
        public string $chave,
        public int $numero,
        public array $dest,
        public int $cnpj_remete,
        public string $nome_remete,
        public string $nome_transp,
        public int $cnpj_transp,
        public Status $status,
        public float $valor,
        public int $volumes,
        public DateTime|string $dt_emis,
        public DateTime|string|null $dt_entrega = '',
        public DateTime|string $dt_estimada = ''
    ) {
        $this->dt_estimada = DateTime::createFromFormat('d/m/Y H:i:s', $this->dt_emis)->add(new DateInterval('P2D'));
        $this->dt_emis = DateTime::createFromFormat('d/m/Y H:i:s', $this->dt_emis);
        $this->dt_entrega = $this->dt_entrega ? DateTime::createFromFormat('d/m/Y H:i:s', $this->dt_entrega) : null;
    }
}
