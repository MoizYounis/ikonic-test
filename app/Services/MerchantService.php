<?php

namespace App\Services;

use App\Jobs\PayoutOrderJob;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MerchantService
{
    protected $merchant;
    protected $user;
    public function __construct()
    {
        $this->merchant = new Merchant();
        $this->user = new User();
    }
    /**
     * Register a new user and associated merchant.
     * Hint: Use the password field to store the API key.
     * Hint: Be sure to set the correct user type according to the constants in the User model.
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return Merchant
     */
    public function register(array $data): Merchant
    {
        // TODO: Complete this method

        // Create new User

        $user = $this->user;

        if (isset($data["name"]) && $data["name"]) {
            $user->name = $data["name"];
        }

        if (isset($data["email"]) && $data["email"]) {
            $user->email = $data["email"];
        }

        if (isset($data["api_key"]) && $data["api_key"]) {
            $user->password = $data["api_key"];
        }

        $user->type = $this->user::TYPE_MERCHANT;

        $user->save();

        // Create merchant
        $merchant = $this->merchant;

        $merchant->user_id = $user->id;

        if (isset($data["domain"]) && $data["domain"]) {
            $merchant->domain = $data["domain"];
        }

        if (isset($data["name"]) && $data["name"]) {
            $merchant->display_name = $data["name"];
        }

        $merchant->save();

        return $merchant;
    }

    /**
     * Update the user
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return void
     */
    public function updateMerchant(User $user, array $data)
    {
        // TODO: Complete this method

        // Update merchant

        if (isset($data["name"]) && $data["name"]) {
            $user->name = $data["name"];
        }

        if (isset($data["email"]) && $data["email"]) {
            $user->email = $data["email"];
        }

        if (isset($data["api_key"]) && $data["api_key"]) {
            $user->password = $data["api_key"];
        }

        $user->save();

        $merchant = $this->merchant->where('user_id', $user->id)->first();

        if (isset($data["domain"]) && $data["domain"]) {
            $merchant->domain = $data["domain"];
        }

        if (isset($data["name"]) && $data["name"]) {
            $merchant->display_name = $data["name"];
        }

        $merchant->save();

        return $merchant;
    }

    /**
     * Find a merchant by their email.
     * Hint: You'll need to look up the user first.
     *
     * @param string $email
     * @return Merchant|null
     */
    public function findMerchantByEmail(string $email): ?Merchant
    {
        // get merchant by email
        $merchant = $this->merchant->whereHas('user', function ($query) use ($email) {
            $query->where('email', $email)
                ->where('type', $this->user::TYPE_MERCHANT);
        })->first();
        return $merchant;
    }

    /**
     * Pay out all of an affiliate's orders.
     * Hint: You'll need to dispatch the job for each unpaid order.
     *
     * @param Affiliate $affiliate
     * @return void
     */
    public function payout(Affiliate $affiliate)
    {
        // TODO: Complete this method

        // Get all unpaid orders for the affiliate
        $unpaid_orders = $affiliate->orders()->where('payout_status', Order::STATUS_UNPAID)->get();

        // Process payout for each unpaid order
        foreach ($unpaid_orders as $order) {
            // Dispatch a job to handle the payout
            PayoutOrderJob::dispatch($order)->onQueue('payouts');
        }
    }
}
