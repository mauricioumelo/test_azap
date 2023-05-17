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
        public float|string $amount = 0.00,
        public float|string $amount_receive = 0.00,
        public float|string $amount_receivable = 0.00,
        public float|string $amount_not_received = 0.00
    ) {
        $this->count_invoices = count($invoices);
        $this->calcAmounts();
        $this->formatInvoices();
    }

    protected function calcAmounts():void
    {
        try {
            foreach ($this->invoices as $invoice) {
                (float) $this->amount += $invoice->valor;
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

            $this->amount = (string) number_format((float) $this->amount, 2, '.', '');
            $this->amount_receive = (string) number_format((float) $this->amount_receive, 2, '.', '');
            $this->amount_receivable = (string) number_format((float) $this->amount_receivable, 2, '.', '');
            $this->amount_not_received = (string) number_format((float) $this->amount_not_received, 2, '.', '');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function formatInvoices():void
    {
        try {
            $formatted_invoice = [];
            foreach ($this->invoices as $invoice) {
                $formatted_invoice[] = $invoice->format();
            }
            $this->invoices = $formatted_invoice;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
