<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Merchant;
use App\Models\Affiliate;
use App\Mail\AffiliateCreated;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\AffiliateCreateException;
use Illuminate\Support\Facades\Log;

class AffiliateService
{
    protected $user;
    protected $merchant;
    protected $affiliate;

    public function __construct(
        protected ApiService $apiService
    ) {
        $this->user = new User();
        $this->merchant = new Merchant();
        $this->affiliate = new Affiliate();
    }

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {
        // TODO: Complete this method
        // Check if the email is already in use by a merchant
        if ($this->user::where('email', $email)->where('type', $this->user::TYPE_MERCHANT)->exists()) {
            throw new AffiliateCreateException("Email address is already in use by a merchant.");
        }

        // Check if the email is already in use by an affiliate
        if ($this->user::where('email', $email)->where('type', $this->user::TYPE_AFFILIATE)->exists()) {
            throw new AffiliateCreateException("Email address is already in use by an affiliate.");
        }

        // Create a new user
        $user = $this->user::create([
            'email' => $email,
            'name' => $name,
            'type' => $this->user::TYPE_AFFILIATE,
        ]);
        // Generate a discount code using the ApiService
        $discountCodeResult = $this->apiService->createDiscountCode($merchant);

        // Create a new affiliate
        $affiliate = $this->affiliate::create([
            'merchant_id' => $merchant->id,
            'user_id' => $user->id,
            'commission_rate' => $commissionRate,
            'discount_code' => $discountCodeResult['code'],
        ]);

        // Send an email notification

        Mail::to($user->email)->send(new AffiliateCreated($affiliate));
        return $affiliate;
    }
}
