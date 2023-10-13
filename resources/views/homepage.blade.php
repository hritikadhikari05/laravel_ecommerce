@include('common/header')
<div class=" px-24  py-10">
    <span class="text-3xl">Trending Products</span>

    <div class="grid grid-cols-4 gap-6 mt-4">
        @foreach($products as $product)
        @include('components/productCard', ['product' => $product])
        @endforeach


    </div>
</div>

@include('common/footer')