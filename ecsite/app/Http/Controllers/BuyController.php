<?php

namespace App\Http\Controllers;

use App\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class BuyController extends Controller
{
    public function index()
    {
        if(Auth::check()){
        $cartitems = CartItem::select('cart_items.*', 'items.name', 'items.amount')
            ->where('user_id', Auth::id())
            ->join('items', 'items.id','=','cart_items.item_id')
            ->get();
        }
        else{
            $cartitems = CartItem::select('cart_items.*', 'items.name', 'items.amount')
            ->where('user_id', '1')
            ->join('items', 'items.id','=','cart_items.item_id')
            ->get();
        }
        $subtotal = 0;
        foreach($cartitems as $cartitem){
            $subtotal += $cartitem->amount * $cartitem->quantity;
        }
        return view('buy/index', ['cartitems' => $cartitems, 'subtotal' => $subtotal]);
    }

    public function store(Request $request)
    {
        if(Auth::check()){
        if( $request->has('post') ){
            CartItem::where('user_id', Auth::id())->delete();
            return view('buy/complete');
        }
    }
    else{
        if( $request->has('post') ){
            CartItem::where('user_id', '1')->delete();
            return view('buy/complete');
        }

    }
        $request->flash();
        return $this->index();
    }
}
