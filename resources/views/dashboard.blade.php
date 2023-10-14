@include('layouts.sidebar')

<div class="p-4 sm:ml-64">
    <span class="text-4xl">Dashboard</span>
    <div class="flex items-center justify-between mt-10">
        <div class=" flex flex-col p-4 bg-blue-300 h-[6rem] w-[20rem] rounded-2xl">
            <span class="text-2xl font-semibold">Total Products</span>
            <span class="text-xl">{{$products->count()}} </span>
        </div>
        <div class=" flex flex-col p-4 bg-green-300 h-[6rem] w-[20rem] rounded-2xl">
            <span class="text-2xl font-semibold">Total Users</span>
            <span class="text-xl">{{$users->count()}} </span>
        </div>
    </div>
</div>
@include('layouts.dashboardFooter')