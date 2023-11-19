<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected $order;
    protected $affiliate;
    protected $merchant;
    public function __construct(
        protected AffiliateService $affiliateService
    ) {
        $this->order = new Order();
        $this->affiliate = new Affiliate();
        $this->merchant = new Merchant();
    }

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method

        // Check for duplicate order_id
        if ($this->order::where('external_order_id', $data['order_id'])->exists()) {
            // Ignore duplicate order
            return;
        }

        // Find or create an affiliate based on the discount_code
        $affiliate = $this->affiliate::firstOrNew(['discount_code' => $data['discount_code']]);

        // If the affiliate is new, associate it with the merchant and save
        if (!$affiliate->exists) {
            $merchant = $this->merchant::firstOrCreate(['domain' => $data['merchant_domain']]);
            $affiliate->merchant()->associate($merchant);
            $affiliate->save();
        }

        // Register the affiliate with the provided customer email and name
        $this->affiliateService->register(
            $affiliate->merchant, // Pass the merchant instance instead of the affiliate
            $data['customer_email'],
            $data['customer_name'],
            0.1
        );

        // Create the order
        $this->order::create([
            'subtotal' => $data['subtotal_price'],
            'merchant_id' => $affiliate->merchant->id,
            'affiliate_id' => $affiliate->id,
            'commission_owed' => $data['subtotal_price'] * $affiliate->commission_rate,
            'external_order_id' => $data['order_id']
        ]);
    }
}
