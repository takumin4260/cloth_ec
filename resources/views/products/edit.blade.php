<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            編集画面
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <section class="text-gray-600 body-font relative">
                    <div class="container px-5 py-24 mx-auto">
                        <div class="flex flex-col text-center w-full mb-12">
                        <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">商品の情報を入力します</h1>
                        </div>
                        <form method="post" action="{{ route('products.update', ['id'=> $product->id ])}}">
                            @csrf

                        <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <div class="flex flex-wrap -m-2">
                                <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">商品名</label>
                                    <input type="text" id="name" name="name" value="{{$product->name}}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                </div>
                                <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">価格</label>
                                    <input type="number" id="price" name="price" value="{{$product->price}}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                </div>
                                <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="discribe" class="leading-7 text-sm text-gray-600">商品詳細</label>
                                    <textarea id="discribe" name="discribe" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{$product->discribe}}</textarea>
                                </div>
                                </div>
                                <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="genre" class="leading-7 text-sm text-gray-600">ジャンル</label><br>
                                    <select name="genre">
                                        <option value="">未選択</option>
                                        <option value="1" @if($product->genre === 1 ) selected @endif>トップス</option>
                                        <option value="2" @if($product->genre === 2 ) selected @endif>ボトムス</option>
                                    </select> 
                                </div>
                                </div>
                                <div class="mt-3 p-2 w-full">
                                <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新する
                                </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </section><br>
                
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
