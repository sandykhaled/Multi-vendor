<?php

namespace App\Observers;

use App\Models\cart;
use Illuminate\Support\Str;

class CartObserver
{
    /**
     * Handle the cart.js "creating" event.
     */
    public function creating(cart $cart)
    {
        $cart->id = Str::uuid();
        $cart->cookie_id = $cart->getCookieId();
    }

    /**
     * Handle the cart.js "updated" event.
     */
    public function updated(cart $cart): void
    {
        //
    }

    /**
     * Handle the cart.js "deleted" event.
     */
    public function deleted(cart $cart): void
    {
        //
    }

    /**
     * Handle the cart.js "restored" event.
     */
    public function restored(cart $cart): void
    {
        //
    }

    /**
     * Handle the cart.js "force deleted" event.
     */
    public function forceDeleted(cart $cart): void
    {
        //
    }
}
