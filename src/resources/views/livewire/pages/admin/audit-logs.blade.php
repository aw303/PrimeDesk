
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-xl font-semibold">Audit Logs</h1>
            <p class="text-sm text-gray-500 mt-1">Create, update, and delete history.</p>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="py-2">Timestamp</th>
                            <th class="py-2">Action</th>
                            <th class="py-2">Model</th>
                            <th class="py-2">Record ID</th>
                            <th class="py-2">User ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b">
                                <td class="py-2">{{ $log['when'] }}</td>
                                <td class="py-2 capitalize">{{ $log['action'] }}</td>
                                <td class="py-2">{{ $log['model'] }}</td>
                                <td class="py-2">{{ $log['id'] }}</td>
                                <td class="py-2">{{ $log['user_id'] ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-3 text-gray-500">No audit entries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
