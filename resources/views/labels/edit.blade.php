<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Label – {{ $label->title }}</h2>
    </x-slot>

    @can('update', $label)
        <div x-data="labelEditor({{ json_encode($label) }})" class="grid md:grid-cols-[360px_1fr] gap-5">
            <form x-ref="form" method="POST" action="{{ route('labels.update', $label) }}"
                class="bg-white rounded-xl p-4 shadow">
                @csrf @method('PUT')
                <input type="hidden" name="title" :value="state.title">
                <input type="hidden" name="data" :value="JSON.stringify(state.data)">
                <input type="hidden" name="theme" :value="JSON.stringify(state.theme)">

                <label class="block text-xs text-gray-500">Label Title</label>
                <input x-model="state.title" class="w-full border rounded-lg px-3 py-2 mb-3" />

                <template x-for="(val,key) in state.data" :key="key">
                    <div class="mb-2">
                        <label class="block text-xs text-gray-500" x-text="key"></label>
                        <textarea x-model="state.data[key]" class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>
                </template>

                <div class="grid grid-cols-3 gap-2 my-3">
                    <div><label class="text-xs">Green</label><input type="color" x-model="state.theme.green"
                            class="w-full h-10"></div>
                    <div><label class="text-xs">Yellow-Orange</label><input type="color" x-model="state.theme.amber"
                            class="w-full h-10"></div>
                    <div><label class="text-xs">Paper</label><input type="color" x-model="state.theme.paper"
                            class="w-full h-10"></div>
                </div>

                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-lg bg-green-700 text-white">Save</button>
                    <a href="{{ route('labels.print', $label) }}" target="_blank"
                        class="px-4 py-2 rounded-lg border border-green-700 text-green-700">Print</a>
                </div>
            </form>

            <div>
                @include('labels.partials.renderer', ['label' => $label])

                <div class="grid grid-cols-4 gap-3 mt-3">
                    @for ($i = 1; $i <= 4; $i++)
                        <form method="POST" action="{{ route('labels.assets.store', [$label, $i]) }}"
                            enctype="multipart/form-data" class="bg-white p-3 rounded-xl border">
                            @csrf
                            <div class="text-xs text-gray-500 mb-1">Badge {{ $i }}</div>
                            <input type="file" name="file" accept="image/*" class="text-xs" />
                            <button class="mt-2 w-full text-center px-2 py-1 rounded bg-gray-800 text-white">Upload</button>
                        </form>
                    @endfor
                </div>
            </div>
        </div>
    @endcan

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('labelEditor', (label) => ({
                state: {
                    title: label.title,
                    theme: label.theme,
                    data: label.data
                }
            }))
        })
    </script>
</x-app-layout>
