<?php

namespace App\Http\Controllers;

use App\Services\AffiliateService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {
    }

    /**
     * Pass the necessary data to the process order method
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: Complete this method

        //data from the incoming request
        $data = [
            'order_id' => $request->input('order_id'),
            'subtotal_price' => $request->input('subtotal_price'),
            'merchant_domain' => $request->input('merchant_domain'),
            'discount_code' => $request->input('discount_code'),
        ];

        // Call the processOrder method from the OrderService
        $this->orderService->processOrder($data);

        // Respond with an OK status
        return $this->sendJson(true, 'Order processed successfully');
    }
}
