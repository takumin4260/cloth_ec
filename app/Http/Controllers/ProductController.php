<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use App\Models\UserStock;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mycart(UserStock $userStock)
    {
        $total_price = $userStock->totalPrice();
        $myCartProducts = $userStock->showMyCart();

        return view('products.mycart',compact('myCartProducts','total_price'));
        
    }

    public function addmycart(Request $request,UserStock $userStock)
    {
        $userId = Auth::id(); 
        $stockId = $request->input('stockId');
        $number = $request->input('number');
        $message = $userStock->addmycart($stockId,$number);
        $myCartProducts = UserStock::where('userId',$userId)->get();

        //再度カート内の合計金額を計算
        $total_price = $userStock->totalPrice();

        return view('products.mycart',compact('myCartProducts' , 'message', 'total_price'));
    }

    public function deleteMyCartStock(Request $request,UserStock $userStock)
    {

        //カートから削除の処理
        $stockId=$request->stockId;
        $message = $userStock->deleteMyCartStock($stockId);

        //追加後の情報を取得
        $myCartProducts = $userStock->showMyCart();

        //再度カート内の合計金額を計算
        $total_price = $userStock->totalPrice();

        return view('products.mycart',compact('myCartProducts' , 'message','total_price'));
    }

    public function checkout(UserStock $userStock)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $session = $userStock->checkout();


        return view('products.checkout',[
            'session' => $session,
            'publicKey' => env('STRIPE_KEY')
        ]);
    }

    public function index()
    {
        $products = Product::select('id','name', 'price')->get();
        return view('products.index', compact('products'));
    }

    public function adminindex()
    {
        // 権限の認証
        Gate::authorize('admin-higher');

        // return view('products.index');
        $products = Product::select('id','name', 'price')->get();
        return view('products.adminindex', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 権限の認証
        Gate::authorize('admin-higher');
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'discribe' => $request->discribe,
            'genre' => $request->genre,
        ]);

        return to_route('products.adminindex');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id); // 1件だけ取得
        if($product->genre === 1 ){ $genre = 'トップス'; }
        if($product->genre === 2 ){ $genre = 'ボトムス'; } 
        return view('products.show', compact('product', 'genre'));
    }

    public function adminshow($id)
    {
        // 権限の認証
        Gate::authorize('admin-higher');

        $product = Product::find($id); // 1件だけ取得
        if($product->genre === 1 ){ $genre = 'トップス'; }
        if($product->genre === 2 ){ $genre = 'ボトムス'; } 
        return view('products.adminshow', compact('product', 'genre'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 権限の認証
        Gate::authorize('admin-higher');

        $product = Product::find($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $product = Product::find($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->discribe = $request->discribe;
        $product->genre = $request->genre;
        $product->save();

        return to_route('products.adminindex'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 権限の認証
        Gate::authorize('admin-higher');

        $product = Product::find($id);
        $product->delete();

        return to_route('products.adminindex'); 
    }
}
