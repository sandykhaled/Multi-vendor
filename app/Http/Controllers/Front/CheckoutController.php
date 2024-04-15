<?php

namespace App\Http\Controllers\Front;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderCreatedNotification;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Nnjeim\World\Models\Country;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {
        $countries = Country::all();
        $countryNames = $countries->pluck('name')->toArray();

        if($cart->get()->count() == 0 ){
            return redirect()->route('home');
        }
        return view('front.checkout',compact(['cart','countryNames']));
    }
    public function store(Request $request,CartRepository $cart)
    {
        $request->validate([
            'address.billing.first_name' => ['required', 'string', 'max:255'],
            'address.billing.last_name' => ['required', 'string', 'max:255'],
            'address.billing.email' => ['required', 'string', 'max:255'],
            'address.billing.phone_number' => ['required', 'string', 'max:255'],
            'address.billing.city' => ['required', 'string', 'max:255'],
        ]);
        $items = $cart->get()->groupBy('product.store_id');

        DB::beginTransaction();
            try {
                foreach ($items as $store_id => $cart_items) {
                    $order = Order::create([
                    'store_id' => $store_id,
                    'user_id' => Auth::id(),
                    'payment_method' => 'cod'
                ]);
                foreach ($cart_items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity
                    ]);
                }
                foreach ($request->post('address') as $type => $address) {
                    $address['type'] = $type;
                    $order->addresses()->create($address);
                }
            }
            $cart->empty();
            DB::commit();
//            event('order.created',$order, Auth::user());
//                event(new OrderCreated($order));
Notification::send(Auth::user(),new OrderCreatedNotification($order));

            }catch (\Throwable $e){
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('home');
    }
}
