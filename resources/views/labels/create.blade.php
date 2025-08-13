<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Create Label</h2>
    </x-slot>

    <form method="POST" action="{{ route('labels.store') }}" class="grid md:grid-cols-3 gap-4">
        @csrf
        @foreach ($templates as $tpl)
            <label class="border rounded-xl p-4 cursor-pointer">
                <input type="radio" name="label_template_id" value="{{ $tpl->id }}" required>
                <div class="font-semibold">{{ $tpl->name }}</div>
                <div class="text-xs text-gray-500">{{ $tpl->width_cm }} × {{ $tpl->height_cm }} cm
                    ({{ $tpl->orientation }})
                </div>
            </label>
        @endforeach
        <input type="hidden" name="title" value="Untitled Label">
        @php
            $firstTemplate = $templates->first();
            $defaults = is_array($firstTemplate->defaults ?? null) ? $firstTemplate->defaults : [];
        @endphp

        <input type="hidden" name="data" value='@json($defaults['keys'] ?? [])'>
        <input type="hidden" name="theme" value='@json($defaults['theme'] ?? [])'>
        <button class="col-span-full bg-green-700 text-white rounded-lg py-2">Create</button>
    </form>

    <script>
        const radios = document.querySelectorAll('input[name=label_template_id]');
        const hiddenData = document.querySelector('input[name=data]');
        const hiddenTheme = document.querySelector('input[name=theme]');
        const tpl = @json($templates->map->only(['id', 'defaults']));
        radios.forEach(r => r.addEventListener('change', e => {
            const t = tpl.find(x => x.id == e.target.value);
            hiddenData.value = JSON.stringify(t.defaults.keys);
            hiddenTheme.value = JSON.stringify(t.defaults.theme);
        }));
    </script>
</x-app-layout>
