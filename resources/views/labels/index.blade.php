<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">My Labels</h2>
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('labels.create') }}" class="bg-green-700 text-white px-4 py-2 rounded">Create New Label</a>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        @forelse($labels as $label)
            <div class="border rounded-lg p-4 bg-white shadow">
                <div class="font-bold">{{ $label->title }}</div>
                <div class="text-sm text-gray-500">{{ $label->template->name }}</div>
                <div class="mt-3 flex gap-2 flex-wrap">
                    @can('view', $label)
                        <a href="{{ route('labels.show', $label) }}"
                            class="px-3 py-1 bg-blue-600 text-white rounded">View</a>
                        <a href="{{ route('labels.print', $label) }}"
                            class="px-3 py-1 bg-yellow-500 text-white rounded">Print</a>
                    @endcan
                    @can('update', $label)
                        <a href="{{ route('labels.edit', $label) }}"
                            class="px-3 py-1 bg-green-600 text-white rounded">Edit</a>
                    @endcan
                    @can('delete', $label)
                        <form method="POST" action="{{ route('labels.destroy', $label) }}"
                            onsubmit="return confirm('Delete label?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                        </form>
                    @endcan
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500">No labels yet.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $labels->links() }}
    </div>
</x-app-layout>
