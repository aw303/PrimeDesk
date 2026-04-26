
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <div class="bg-white p-5 shadow-sm rounded-lg">
                <p class="text-xs uppercase text-gray-500">Customers</p>
                <p class="text-3xl font-semibold mt-2">{{ $metrics['customers'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-5 shadow-sm rounded-lg">
                <p class="text-xs uppercase text-gray-500">Leads</p>
                <p class="text-3xl font-semibold mt-2">{{ $metrics['leads'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-5 shadow-sm rounded-lg">
                <p class="text-xs uppercase text-gray-500">Conversion Rate</p>
                <p class="text-3xl font-semibold mt-2">{{ $metrics['conversion_rate'] ?? 0 }}%</p>
            </div>
            <div class="bg-white p-5 shadow-sm rounded-lg">
                <p class="text-xs uppercase text-gray-500">Open Deals</p>
                <p class="text-3xl font-semibold mt-2">{{ $metrics['open_deals'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-5 shadow-sm rounded-lg">
                <p class="text-xs uppercase text-gray-500">Won Revenue</p>
                <p class="text-3xl font-semibold mt-2">${{ number_format($metrics['won_revenue'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white p-5 shadow-sm rounded-lg">
                <p class="text-xs uppercase text-gray-500">Pending Tasks</p>
                <p class="text-3xl font-semibold mt-2">{{ $metrics['pending_tasks'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
