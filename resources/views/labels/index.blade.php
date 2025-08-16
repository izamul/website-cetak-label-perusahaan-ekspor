<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-900">My Labels</h2>

            {{-- <-- tombol header: pakai ! (important) biar tidak ketiban style header --}}
            <a href="{{ route('labels.create') }}"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold
                !bg-green-600 !text-white hover:!bg-green-700 focus:outline-none
                focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        {{-- fallback bar di mobile --}}
        <div class="mb-4 flex items-center justify-between sm:hidden">
            <span class="text-lg font-semibold text-gray-900">My Labels</span>
            <a href="{{ route('labels.create') }}"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold
                !bg-green-600 !text-white hover:!bg-green-700">
                Create
            </a>
        </div>

        @if (session('ok'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('ok') }}
            </div>
        @endif

        @forelse ($labels as $label)
            <div
                class="mb-4 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="hidden sm:block">
                            <div
                                class="w-28 h-20 rounded border border-gray-200 bg-gray-50 flex items-center justify-center">
                                <span class="text-[10px] text-gray-500 leading-tight text-center">
                                    {{ optional($label->template)->width_cm }}×{{ optional($label->template)->height_cm }}
                                    cm<br>
                                    {{ ucfirst(optional($label->template)->orientation) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $label->title }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ optional($label->template)->name }}
                                @if ($label->template)
                                    — {{ $label->template->width_cm }}×{{ $label->template->height_cm }} cm
                                    ({{ $label->template->orientation }})
                                @endif
                            </p>
                            <p class="mt-1 text-xs text-gray-400">Updated {{ $label->updated_at?->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('labels.show', $label) }}"
                            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">View</a>
                        <a href="{{ route('labels.print', $label) }}" target="_blank"
                            class="inline-flex items-center rounded-lg border border-amber-300 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100">Print</a>
                        <a href="{{ route('labels.edit', $label) }}"
                            class="inline-flex items-center rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:bg-green-700">Edit</a>
                        <form method="POST" action="{{ route('labels.destroy', $label) }}"
                            onsubmit="return confirm('Delete this label permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center">
                <p class="text-gray-600 mb-4">You don’t have any labels yet.</p>
                <a href="{{ route('labels.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-white text-sm font-semibold hover:bg-green-700">Create
                    your first label</a>
            </div>
        @endforelse

        <div class="mt-6">{{ $labels->links() }}</div>
    </div>
</x-app-layout>
