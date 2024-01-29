<x-app-layout>
    <div class="container-fluid">
        <div class="mx-auto" style="max-width:1200px">
            <h1 style="color:#555555; text-align:center; font-size:1.2em; padding:24px 0px; font-weight:bold;">{{ Auth::user()->name }}さんのカートの中身</h1>
            <p class="text-center">{{ $message ?? '' }}</p><br>
            <div class="text-center">合計金額<br>
            {{$total_price}}円</div>
            <div class="">
                <div class="">
                    {{-- 追加 --}}      
                    @foreach($myCartProducts as $product)
                <div class="text-center rounded shadow-lg bg-white p-6 m-4">
                {{$product->product->name}} <br>                                
                {{ number_format($product->product->price)}}円 <br>
                {{number_format($product->number)}}個 <br>  
                    <div class="incart flex justify-center p-4 m-4">
                    </div>
                    <form  method="post" action="{{ route('products.deleteMyCartStock')}}">
                        @csrf
                        <input type="hidden" name="stockId" value="{{ $product->product->id }}">
                        <input type="submit" value="カートから削除する">
                    </form>
                </div>
                    @endforeach

                    <div class="text-center"><button onClick="location.href='{{ route('products.checkout') }}'" class="cart__purchase btn btn-primary">
                        購入する
                    </button></div>
                    

                    @if($myCartProducts->isEmpty())
                        <p class="text-center">カートはからっぽです。</p>
                    @endif
                    {{-- ここまで --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>