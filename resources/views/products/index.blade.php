<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3>商品一覧</h3><br>

                </div>
                <section class="text-gray-600 body-font">
                    <div class="container px-5 pb-24 mx-auto">
                        <div class="flex flex-wrap -m-4">
                        @foreach($products as $product)

                            <div class="p-4 md:w-1/3">
                                <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                                <!-- <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog"> -->
                                <div class="p-6">
                                    <h1 class="title-font text-lg font-medium text-gray-900 mb-3">{{$product->name}}</h1>
                                    <p class="leading-relaxed mb-3">{{$product->price}}</p>
                                    <p class="leading-relaxed mb-3"><a href="{{route('products.show',['id'=>$product->id])}}">詳細を見る</a></p>
                                    <form action="{{ route('products.addmycart')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="stockId" value="{{ $product->id }}">
                                        <input type="number" name="number" id="number" ><br>
                                        <input type="submit" value="カートに入れる">
                                    </form>

                                </div>
                                </div>
                            </div>
                        @endforeach

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
