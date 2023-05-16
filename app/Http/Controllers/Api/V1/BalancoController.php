<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Entity\Senders\SenderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BalancoController extends Controller
{
    public function __construct(
      private SenderService $senderService
    ) {
    }

    public function groupInvoices():JsonResponse
    {
        try {
            $data = $this->senderService->getAllSenders();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'success in listing invoices by group',
                    'data'=> $data,
                ], Response::HTTP_ACCEPTED
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'success in listing invoices by group',
                    'data'=> $th->getMessage(),
                ], Response::HTTP_BAD_REQUEST
            );
        }
    }
}
