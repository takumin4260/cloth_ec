<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品一覧（管理者用）
        </h2>
    </x-slot>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    index<br>
                    <a href="{{route('products.create')}}" class="text-blue-500">商品登録</a>
                </div>
                <section class="text-gray-600 body-font">
                    <div class="container px-5 py-24 mx-auto">
                        <div class="flex flex-wrap -m-4">
                        @foreach($products as $product)

                            <div class="p-4 md:w-1/3">
                                <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                                <!-- <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog"> -->
                                <div class="p-6">
                                    <h2 class="tracking-widest text-xs title-font font-medium text-gray-400 mb-1">{{$product->id}}</h2>
                                    <h1 class="title-font text-lg font-medium text-gray-900 mb-3">{{$product->name}}</h1>
                                    <p class="leading-relaxed mb-3">{{$product->price}}円</p>
                                    <p class="leading-relaxed mb-3"><a href="{{route('products.adminshow',['id'=>$product->id])}}">詳細を見る</a></p>
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
