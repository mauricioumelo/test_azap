<?php

namespace App\Domain\Entity\Senders;

use App\Domain\Entity\Invoices\Invoice;
use App\Domain\Enum\Invoice\Status;
use Exception;
use Illuminate\Support\Facades\Http;

class SenderService
{
    public function __construct(private string $azapfy_url = '')
    {
        $this->azapfy_url = env('AZAPFY_URL', 'http://homologacao3.azapfy.com.br/api/ps/notas');
    }

    protected function getInvoicesGroupBySender():array
    {
        $response = Http::get($this->azapfy_url);

        if ($response->failed()) {
            throw new Exception($response->throw(), 1);
        }

        return collect($response->json())->groupBy(['nome_remete'])->toArray();
    }

    public function getAllSenders(): array
    {
        $senders = $this->toSenders($this->getInvoicesGroupBySender());

        return $senders;
    }

    /**
     * Function transform invoices to senders
     *
     * @param array $invoices
     *
     * @return array[Senders]
     */
    private function toSenders(array $invoicesBySender):array
    {
        try {
            $sendersEntity = [];

            foreach ($invoicesBySender as $invoices) {
                $invoicesEntity = [];
                foreach ($invoices as $invoice) {
                    $invoicesEntity[] = new Invoice(
                        chave: $invoice['chave'],
                        numero: $invoice['numero'],
                        dest: $invoice['dest'],
                        cnpj_remete: $invoice['cnpj_remete'],
                        nome_remete: $invoice['nome_remete'],
                        nome_transp: $invoice['nome_transp'],
                        cnpj_transp: $invoice['cnpj_transp'],
                        status:  constant(Status::class.'::'.$invoice['status']),
                        valor: $invoice['valor'],
                        volumes: $invoice['volumes'],
                        dt_emis: $invoice['dt_emis'],
                        dt_entrega: isset($invoice['dt_entrega']) ? $invoice['dt_entrega'] : null,
                    );
                }
                $sendersEntity[] = new Sender(nome: $invoices[0]['nome_remete'], cnpj:(int) $invoices[0]['cnpj_remete'], invoices:$invoicesEntity);
            }

            return $sendersEntity;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
