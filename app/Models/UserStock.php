<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserStock extends Model
{
    use HasFactory;

    public function showMyCart()
    {
        $userId = Auth::id();
        return $this->where('userId',$userId)->with('product')->get();
    }

    public function totalPrice()
    {
        $userId = Auth::id();
        $cart = $this->where('userId', $userId)->with('product')->get()->toArray();
        //配列形式で、該当するuserテーブルからレコードを取得

        $total_price = 0;
        foreach ($cart as $products) {
            $total_price += $products["product"]["price"] * $products["number"];
        }
        return $total_price;
    }

    public function product()
    {
        return $this->belongsTo('\App\Models\Product','stockId');
    }

    

    public function addmycart($stockId,$number)
    {
        $userId = Auth::id(); 
        $this->where('userId', $userId)->where('stockId',$stockId)->delete();
        $cartAddInfo = $this->firstOrCreate(['stockId' => $stockId,'userId' => $userId, 'number' =>$number]);

        if($cartAddInfo->wasRecentlyCreated){
            $message = 'カートに追加しました';
        }
        else{
            $message = 'すでにカートに入っています';
        }

        return $message;
    }

    public function deleteMyCartStock($stockId)
    {
        $userId = Auth::id(); 
        $deleteStockCount = $this->where('userId', $userId)->where('stockId',$stockId)->delete();
        
        if($deleteStockCount > 0){
            $message = 'カートから一つの商品を削除しました';
        }else{
            $message = '削除に失敗しました';
        }
        return $message;
    }

    public function checkout()
    {
        $userId = Auth::id();
        $cart = UserStock::where('userId', $userId)->with('product')->get()->toArray();
        $line_items = [];
        foreach ($cart as $products) {
            $line_item = [
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => $products["product"]["price"],
                    'product_data' => [
                        'name' => $products["product"]["name"],
                        'description' => $products["product"]["discribe"],
                    ],
                ],
                'quantity'    => $products["number"],
            ];

            array_push($line_items, $line_item);
            
        }

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [$line_items],
            'success_url'          => route('products.index'),
            'cancel_url'           => route('products.mycart'),
            'mode'                 => 'payment',
        ]);

        return $session;
    }

    protected $guarded = [
        'id'
    ];
}
