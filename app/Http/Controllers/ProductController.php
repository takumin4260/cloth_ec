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
        $userId = Auth::id();
        $cart = UserStock::where('userId', $userId)->with('product')->get()->toArray();
        //配列形式で、該当するuserテーブルからレコードを取得

        $total_price = 0;
        foreach ($cart as $products) {
            $total_price += $products["product"]["price"] * $products["number"];
        }
        $myCartProducts = $userStock->showMyCart();

        return view('products.mycart',compact('myCartProducts','total_price'));
        
    }

    public function addmycart(Request $request)
    {
        $userId = Auth::id(); 
        $stockId = $request->input('stockId');
        $number = $request->input('number');
        UserStock::where('userId', $userId)->where('stockId',$stockId)->delete();
        $cartAddInfo = UserStock::firstOrCreate(['stockId' => $stockId,'userId' => $userId, 'number' =>$number]);

        if($cartAddInfo->wasRecentlyCreated){
            $message = 'カートに追加しました';
        }
        else{
            $message = 'すでにカートに入っています';
        }
        $myCartProducts = UserStock::where('userId',$userId)->get();

        //再度カート内の合計金額を計算

        $userId = Auth::id();
        $cart = UserStock::where('userId', $userId)->with('product')->get()->toArray();
        //配列形式で、該当するuserテーブルからレコードを取得

        $total_price = 0;
        foreach ($cart as $products) {
            $total_price += $products["product"]["price"] * $products["number"];
        }

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
        
        $userId = Auth::id();
        $cart = UserStock::where('userId', $userId)->with('product')->get()->toArray();
        //配列形式で、該当するuserテーブルからレコードを取得

        $total_price = 0;
        foreach ($cart as $products) {
            $total_price += $products["product"]["price"] * $products["number"];
        }

        return view('products.mycart',compact('myCartProducts' , 'message','total_price'));
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


        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [$line_items],
            'success_url'          => route('products.index'),
            'cancel_url'           => route('products.mycart'),
            'mode'                 => 'payment',
        ]);

        return view('products.checkout',[
            'session' => $session,
            'publicKey' => env('STRIPE_KEY')
        ]);
    }

    public function index()
    {
        // return view('products.index');
        $products = Product::select('id','name', 'price')->get();
        return view('products.index', compact('products'));
    }

    public function adminindex()
    {
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
        // dd($request);
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
        dd($product);
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

        $product = Product::find($id);
        $product->delete();

        return to_route('products.adminindex'); 
    }
}
