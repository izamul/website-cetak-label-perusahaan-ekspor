<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-900">My Labels</h2>

            <a href="{{ route('labels.create') }}" class="btnx btnx--green">
                {{-- plus icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M11 4a1 1 0 1 1 2 0v7h7a1 1 0 1 1 0 2h-7v7a1 1 0 1 1-2 0v-7H4a1 1 0 1 1 0-2h7V4z" />
                </svg>
                Create
            </a>
        </div>
    </x-slot>

    {{-- Scoped styles for nice buttons + spacing --}}
    <style>
        .btnx {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem 1rem;
            border-radius: .75rem;
            font-weight: 700;
            text-decoration: none;
            line-height: 1;
            border: 1px solid transparent;
            transition: .15s ease;
        }

        .btnx svg {
            width: 1rem;
            height: 1rem
        }

        .btnx--outline {
            background: #fff;
            color: #374151;
            border-color: #D1D5DB
        }

        .btnx--outline:hover {
            background: #F9FAFB;
            color: #111827
        }

        .btnx--outline:focus-visible {
            outline: 2px solid transparent;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, .15)
        }

        .btnx--amber {
            background: #FEF3C7;
            color: #92400E;
            border-color: #FCD34D
        }

        .btnx--amber:hover {
            background: #FDE68A
        }

        .btnx--amber:focus-visible {
            outline: 2px solid transparent;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .35)
        }

        .btnx--green {
            background: #16a34a;
            color: #fff;
            border-color: #15803d;
            box-shadow: 0 2px 6px rgba(22, 163, 74, .25)
        }

        .btnx--green:hover {
            background: #15803d
        }

        .btnx--green:focus-visible {
            outline: 2px solid transparent;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .35)
        }

        .btnx--red {
            background: #dc2626;
            color: #fff;
            border-color: #b91c1c;
            box-shadow: 0 2px 6px rgba(220, 38, 38, .25)
        }

        .btnx--red:hover {
            background: #b91c1c
        }

        .btnx--red:focus-visible {
            outline: 2px solid transparent;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .35)
        }

        /* header fallback mobile button */
        .btnx--sm {
            padding: .45rem .8rem;
            border-radius: .65rem;
            font-weight: 700
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        {{-- Mobile title + create --}}
        <div class="mb-4 flex items-center justify-between sm:hidden">
            <span class="text-lg font-semibold text-gray-900">My Labels</span>
            <a href="{{ route('labels.create') }}" class="btnx btnx--green btnx--sm">Create</a>
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

                    {{-- ACTIONS: bigger gaps & clear colors --}}
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        <a href="{{ route('labels.show', $label) }}" class="btnx btnx--outline">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 5c5 0 9 4 10 7-1 3-5 7-10 7S3 15 2 12c1-3 5-7 10-7zm0 2a5 5 0 1 0 .001 10.001A5 5 0 0 0 12 7z" />
                            </svg>
                            View
                        </a>

                        <a href="{{ route('labels.print', $label) }}" target="_blank" class="btnx btnx--amber">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M6 9V4h12v5h2a2 2 0 0 1 2 2v5h-4v4H8v-4H4v-5a2 2 0 0 1 2-2h0zm2 10h8v-4H8v4zm8-10V6H8v3h8z" />
                            </svg>
                            Print
                        </a>

                        <a href="{{ route('labels.edit', $label) }}" class="btnx btnx--green">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M21.7 2.3a2.6 2.6 0 0 0-3.7 0L16.9 3.4l3.7 3.7 1.1-1.1a2.6 2.6 0 0 0 0-3.7zM19.1 8.6 15.4 4.9 4.5 15.8V19.5h3.7L19.1 8.6z" />
                            </svg>
                            Edit
                        </a>

                        <form method="POST" action="{{ route('labels.destroy', $label) }}"
                            onsubmit="return confirm('Delete this label permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btnx btnx--red">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M9 3a1 1 0 0 0-1 1v1H5.5a1 1 0 1 0 0 2H6v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7h.5a1 1 0 1 0 0-2H16V4a1 1 0 0 0-1-1H9zm2 4a1 1 0 0 0-1 1v9a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v9a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1z" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center">
                <p class="text-gray-600 mb-4">You don’t have any labels yet.</p>
                <a href="{{ route('labels.create') }}" class="btnx btnx--green">Create your first label</a>
            </div>
        @endforelse

        <div class="mt-6">{{ $labels->links() }}</div>
    </div>
</x-app-layout>
