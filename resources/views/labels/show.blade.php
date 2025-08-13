<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $label->title }}</h2>
    </x-slot>

    @can('view', $label)
        @include('labels.partials.renderer', ['label' => $label])

        <div class="mt-4 flex gap-2">
            @can('update', $label)
                <a href="{{ route('labels.edit', $label) }}" class="px-4 py-2 bg-green-700 text-white rounded">Edit</a>
            @endcan
            @can('delete', $label)
                <form method="POST" action="{{ route('labels.destroy', $label) }}" onsubmit="return confirm('Delete label?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
                </form>
            @endcan
        </div>
    @endcan
</x-app-layout>
