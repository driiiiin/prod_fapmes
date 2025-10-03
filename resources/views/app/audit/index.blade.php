<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Audit Logs</h1>
        <form method="GET" action="" class="mb-4 flex flex-wrap gap-2 items-end">
            <div>
                <label for="user_id" class="block text-sm font-medium">User ID</label>
                <input type="text" name="user_id" id="user_id" value="{{ request('user_id') }}" class="form-input rounded border-gray-300" />
            </div>
            <div>
                <label for="auditable_type" class="block text-sm font-medium">Model</label>
                <input type="text" name="auditable_type" id="auditable_type" value="{{ request('auditable_type') }}" class="form-input rounded border-gray-300" />
            </div>
            <div>
                <label for="event" class="block text-sm font-medium">Event</label>
                <select name="event" id="event" class="form-select rounded border-gray-300">
                    <option value="">All</option>
                    <option value="created" @if(request('event')=='created') selected @endif>Created</option>
                    <option value="updated" @if(request('event')=='updated') selected @endif>Updated</option>
                    <option value="deleted" @if(request('event')=='deleted') selected @endif>Deleted</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium">From</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-input rounded border-gray-300" />
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium">To</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-input rounded border-gray-300" />
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
            </div>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-2 py-1 border">ID</th>
                        <th class="px-2 py-1 border">User ID</th>
                        <th class="px-2 py-1 border">Model</th>
                        <th class="px-2 py-1 border">Model ID</th>
                        <th class="px-2 py-1 border">Event</th>
                        <th class="px-2 py-1 border">Old Values</th>
                        <th class="px-2 py-1 border">New Values</th>
                        <th class="px-2 py-1 border">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                    <tr>
                        <td class="px-2 py-1 border">{{ $audit->id }}</td>
                        <td class="px-2 py-1 border">{{ $audit->user_id }}</td>
                        <td class="px-2 py-1 border">{{ class_basename($audit->auditable_type) }}</td>
                        <td class="px-2 py-1 border">{{ $audit->auditable_id }}</td>
                        <td class="px-2 py-1 border">{{ $audit->event }}</td>
                        <td class="px-2 py-1 border text-xs max-w-xs overflow-x-auto">{{ Str::limit(json_encode($audit->old_values), 100) }}</td>
                        <td class="px-2 py-1 border text-xs max-w-xs overflow-x-auto">{{ Str::limit(json_encode($audit->new_values), 100) }}</td>
                        <td class="px-2 py-1 border">{{ $audit->created_at }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No audit logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $audits->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
