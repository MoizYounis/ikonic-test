<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Enum\ResponseMessages;
use Illuminate\Support\Carbon;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\MerchantRequest;

class MerchantController extends Controller
{
    protected $order;
    public function __construct(
        MerchantService $merchantService
    ) {
        $this->order = new Order();
    }
    /**
     * Useful order statistics for the merchant API.
     *
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
        // TODO: Complete this method

        // from and to dates from the request
        $from = $request->input('from');
        $to = $request->input('to');

        // Get orders within the specified date range
        $orders = $this->order::where('merchant_id', auth()->user()->merchant->id)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        // Filter orders with an affiliate
        $ordersWithAffiliate = $orders->where('affiliate_id', '!=', null);

        // Calculate the count, revenue, and commissions_owed
        $count = $orders->count();
        $revenue = $orders->sum('subtotal');
        $commissionsOwed = $ordersWithAffiliate->sum('commission_owed');

        // Prepare the response data
        $data = [
            'count' => $count,
            'revenue' => $revenue,
            'commissions_owed' => $commissionsOwed,
        ];

        // Return the response as JSON
        return response()->json($data);
    }
}
