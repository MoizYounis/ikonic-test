<?php

namespace App\Services;

use App\Jobs\PayoutOrderJob;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

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
        $user = $this->user;

        if (isset($data["name"]) && $data["name"]) {
            $user->name = $data["name"];
        }

        if (isset($data["email"]) && $data["email"]) {
            $user->email = $data["email"];
        }

        if (isset($data["password"]) && $data["password"]) {
            $user->password = $data["password"];
        }

        $user->type = $this->user::TYPE_MERCHANT;

        $user->save();

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
        // TODO: Complete this method
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
    }
}
