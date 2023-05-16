<?php

namespace App\Domain\Entity\Senders;

use App\Domain\Enum\Invoice\Status;

class Sender
{
    /**
     * Undocumented function
     *
     * @param string $nome
     * @param int $cnpj
     * @param array[Invoice] $invoices
     */
    public function __construct(
        public string $nome,
        public int $cnpj,
        public array $invoices,
        public int $count_invoices = 0,
        public float|string $amount_receive = 0.00,
        public float|string $amount_receivable = 0.00,
        public float|string $amount_not_received = 0.00
    ) {
        $this->count_invoices = count($invoices);
        $this->calcAmounts();
    }

    protected function calcAmounts():void
    {
        try {
            foreach ($this->invoices as $invoice) {
                if ($invoice->status === Status::COMPROVADO && ! empty($invoice->dt_entrega)) {
                    if ($invoice->dt_estimada < $invoice->dt_entrega) {
                        (float) $this->amount_not_received += $invoice->valor;
                        continue;
                    }
                    $this->amount_receive += $invoice->valor;
                    continue;
                }

                $this->amount_receivable += $invoice->valor;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
