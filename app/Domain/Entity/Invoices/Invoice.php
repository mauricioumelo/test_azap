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

    public function format(): array
    {
        return [
            'chave' => (string) $this->chave,
            'numero' => (string) $this->numero,
            'dest' => $this->dest,
            'cnpj_remete' => (string) $this->cnpj_remete,
            'nome_remete' => (string) $this->nome_remete,
            'nome_transp' => (string) $this->nome_transp,
            'cnpj_transp' => (string) $this->cnpj_transp,
            'status' => $this->status->value,
            'valor' => (string) number_format((float) $this->valor, 2, '.', ''),
            'volumes' => (string) $this->volumes,
            'dt_emis' => $this->dt_emis->format('d/m/Y H:i:s'),
            'dt_entrega' => $this->dt_entrega ? $this->dt_entrega->format('d/m/Y H:i:s') : null,
        ];
    }
}
