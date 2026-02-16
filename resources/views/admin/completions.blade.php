@extends('layouts.admin')

@section('title', 'Task Completions')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Task Completions</h1>
                <p class="mt-1 text-sm text-gray-500">Pending task completions awaiting review</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Admin</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pending Completions</h3>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($completions as $completion)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $completion->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($completion->task)->title ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($completion->user)->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $completion->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ url('/admin/completions/'.$completion->id.'/approve') }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded bg-green-600 text-white text-xs">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ url('/admin/completions/'.$completion->id.'/reject') }}">
                                        @csrf
                                        <input type="hidden" name="notes" value="Rejected by admin">
                                        <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-xs">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-gray-500">No pending completions found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    {{ $completions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
