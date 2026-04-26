
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Reports</h1>
            <p class="text-sm text-gray-500 mt-1">KPI and pipeline reporting.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Leads</p>
                    <p class="text-2xl font-semibold">{{ $metrics['leads'] ?? 0 }}</p>
                </div>
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Conversion Rate</p>
                    <p class="text-2xl font-semibold">{{ $metrics['conversion_rate'] ?? 0 }}%</p>
                </div>
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Won Revenue</p>
                    <p class="text-2xl font-semibold">${{ number_format($metrics['won_revenue'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="font-semibold">Lead Status Breakdown</h2>
                <div class="mt-4 space-y-2">
                    @forelse($leadStatus as $status => $total)
                        <div class="flex items-center justify-between text-sm">
                            <span class="capitalize">{{ $status }}</span>
                            <span class="font-medium">{{ $total }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No lead data.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="font-semibold">Deal Stage Breakdown</h2>
                <div class="mt-4 space-y-2">
                    @forelse($dealStage as $stage => $total)
                        <div class="flex items-center justify-between text-sm">
                            <span class="capitalize">{{ $stage }}</span>
                            <span class="font-medium">{{ $total }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No deal data.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
