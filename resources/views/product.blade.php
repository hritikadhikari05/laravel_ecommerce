@include('common/header')
<div class=" px-24  py-10">
    <!-- <span class="text-3xl">Trending Products</span> -->

    <div class="grid grid-cols-2 gap-6 mt-4">

        <div>
            <img src="{{asset('storage/'.$product->image_path) }}" class="h-[30rem] w-[30rem]" alt="">
        </div>
        <div>
            <h1 class="text-2xl font-semibold">{{$product->productTitle}}</h1>
            <p class="text-xl font-semibold mt-2 text-red-500">{{$product->manufacturer}}</p>
            <p class="text-xl font-semibold mt-2">{{$product->category}}</p>
            <p class="text-xl font-normal mt-2">{{$product->description}}</p>
            <p class="text-xl font-semibold mt-2">{{$product->discount}}</p>
            <p class="text-xl font-semibold line-through mt-2">${{$product->price}}</p>
            <!--Buy Now Button  -->
            <button type="button" class="focus:outline-none text-white bg-yellow-400 w-52 mt-5 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-3xl text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900">Buy Now</button>
        </div>

    </div>
</div>

@include('common/footer')