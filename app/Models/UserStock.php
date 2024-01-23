<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserStock extends Model
{
    use HasFactory;

    public function showMyCart()
    {
        $userId = Auth::id();
        return $this->where('userId',$userId)->with('product')->get();
    }

    public function product()
    {
        return $this->belongsTo('\App\Models\Product','stockId');
    }

    public function addmycart($stockId)
    {
        $userId = Auth::id(); 
        $cartAddInfo = $this->firstOrCreate(['stockId' => $stockId,'userId' => $userId]);

        if($cartAddInfo->wasRecentlyCreated){
            $message = 'カートに追加しました';
        }
        else{
            $message = 'カートに登録済みです';
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
    protected $guarded = [
        'id'
    ];
}
