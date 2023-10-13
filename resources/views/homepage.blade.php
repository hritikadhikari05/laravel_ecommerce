@include('common/header')
<div class=" px-24  py-10">
    <span class="text-3xl">Trending Products</span>

    <div class="grid grid-cols-4 gap-6 mt-4">
        @include('components/productCard')
        @include('components/productCard')
        @include('components/productCard')
        @include('components/productCard')
        @include('components/productCard')
        @include('components/productCard')

    </div>
</div>

@include('common/footer')