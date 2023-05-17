<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Entity\Senders\SenderService;
use App\Http\Controllers\Controller;
use App\UseCase\DTO\Senders\ListSendersInputDto;
use App\Utils\ResponseApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SendersController extends Controller
{
    public function __construct(
        private SenderService $senderService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->senderService->getAllSenders(listSendersInputDto: new ListSendersInputDto(
                order: $request->order ?: '',
                page: $request->page ?: 1,
                limit: $request->limit ?: 10
            ));

            return ResponseApi::success($data->toArray(), 'success in listing invoices by group');
        } catch (\Throwable $th) {
            return ResponseApi::fail($th->getMessage(), 'Error in the list of invoices by group');
        }
    }
}
