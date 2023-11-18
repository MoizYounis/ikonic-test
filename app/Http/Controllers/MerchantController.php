<?php

namespace App\Http\Controllers;

use App\Enum\ResponseMessages;
use App\Http\Requests\MerchantRequest;
use App\Models\Merchant;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MerchantController extends Controller
{
    protected $merchant;
    public function __construct(
        MerchantService $merchantService
    ) {
        $this->merchant = $merchantService;
    }

    public function register(MerchantRequest $request)
    {
        try {
            DB::beginTransaction();
            $merchant = $this->merchant->register($request->prepareRequest());
            if ($merchant) {
                DB::commit();
                return $this->sendJson(true, "Merchant Registered Successfully!");
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("merchant/register", $request->all(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
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
        return $this->sendJson(true, "Success");
    }
}
