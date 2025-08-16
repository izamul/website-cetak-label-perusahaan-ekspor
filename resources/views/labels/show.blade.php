{{-- resources/views/labels/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ url()->previous() ?: route('labels.index') }}"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-300 text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    {{-- back icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Back</span>
                </a>

                <h2 class="font-semibold text-xl text-gray-900 truncate">{{ $label->title }}</h2>
            </div>
        </div>
    </x-slot>

    @php
        $tpl = $label->template;
        $codeLower = strtolower((string) ($tpl->code ?? ''));
        $isLandscape = ($tpl->width_cm ?? 0) > ($tpl->height_cm ?? 0);
        $renderer =
            $codeLower === 'anchor' || $isLandscape ? 'labels.partials.renderer_anchor' : 'labels.partials.renderer';
    @endphp

    {{-- Scoped button styles to avoid clashes --}}
    <style>
        .btnx {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem 1rem;
            border-radius: .6rem;
            font-weight: 600;
            line-height: 1;
            text-decoration: none;
            transition: .15s ease;
        }

        .btnx:focus-visible {
            outline: 2px solid transparent;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, .08);
        }

        .btnx-back {
            border: 1px solid #D1D5DB;
            color: #374151;
            background: #fff;
        }

        .btnx-back:hover {
            background: #F9FAFB;
            color: #111827;
        }

        .btnx-update {
            background: #16a34a;
            color: #fff;
            border: 1px solid #15803d;
            box-shadow: 0 2px 6px rgba(22, 163, 74, .25);
        }

        .btnx-update:hover {
            background: #15803d;
        }

        .btnx-update:focus-visible {
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .35);
        }

        .btnx-delete {
            background: #dc2626;
            color: #fff;
            border: 1px solid #b91c1c;
            box-shadow: 0 2px 6px rgba(220, 38, 38, .25);
        }

        .btnx-delete:hover {
            background: #b91c1c;
        }

        .btnx-delete:focus-visible {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .35);
        }

        .btnx svg {
            width: 1rem;
            height: 1rem;
        }
    </style>

    @can('view', $label)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="rounded-2xl border border-dashed border-gray-200 bg-slate-50 p-4">
                @include($renderer, ['label' => $label])
            </div>

            {{-- Bottom actions only --}}
            <div class="mt-4 flex flex-wrap gap-4">
                <a href="{{ url()->previous() ?: route('labels.index') }}" class="btnx btnx-back">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 19l-7-7 7-7M3 12h18" />
                    </svg>
                    Back
                </a>

                @can('update', $label)
                    <a href="{{ route('labels.edit', $label) }}" class="btnx btnx-update">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M21.7 2.3a2.6 2.6 0 0 0-3.7 0L16.9 3.4l3.7 3.7 1.1-1.1a2.6 2.6 0 0 0 0-3.7zM19.1 8.6 15.4 4.9 4.5 15.8V19.5h3.7L19.1 8.6z" />
                        </svg>
                        Update
                    </a>
                @endcan

                @can('delete', $label)
                    <form method="POST" action="{{ route('labels.destroy', $label) }}"
                        onsubmit="return confirm('Delete label?')">
                        @csrf @method('DELETE')
                        <button class="btnx btnx-delete" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M9 3a1 1 0 0 0-1 1v1H5.5a1 1 0 1 0 0 2H6v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7h.5a1 1 0 1 0 0-2H16V4a1 1 0 0 0-1-1H9zm2 4a1 1 0 0 0-1 1v9a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v9a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1z" />
                            </svg>
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    @endcan
</x-app-layout>
