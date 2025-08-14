{{-- resources/views/labels/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Create Label</h2>
    </x-slot>

    <form method="POST" action="{{ route('labels.store') }}" class="grid md:grid-cols-2 gap-6">
        @csrf

        {{-- Pilihan Template --}}
        <div>
            <h3 class="font-semibold mb-3">Pilih Template</h3>
            <div class="grid gap-4">
                @foreach ($templates as $tpl)
                    <label class="border rounded-xl p-4 cursor-pointer flex items-start gap-2">
                        <input type="radio" name="label_template_id" value="{{ $tpl->id }}" required
                            {{ $loop->first ? 'checked' : '' }}>
                        <div>
                            <div class="font-semibold">{{ $tpl->name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $tpl->width_cm }} x {{ $tpl->height_cm }} cm ({{ $tpl->orientation }})
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Preview Label --}}
        <div>
            <h3 class="font-semibold mb-3">Preview Label</h3>
            <div id="label-preview" class="border p-4 bg-white">
                @include('labels.partials.renderer', ['label' => $label])
            </div>
        </div>

        {{-- Hidden Input untuk submit --}}
        <input type="hidden" name="title" value="{{ $label->title }}">
        <input type="hidden" name="data" value='@json($label->data)'>
        <input type="hidden" name="theme" value='@json($label->theme)'>

        <div class="col-span-full">
            <button class="bg-green-700 text-white rounded-lg py-2 px-4">Create</button>
        </div>
    </form>

    <script>
        const radios = document.querySelectorAll('input[name=label_template_id]');
        const hiddenData = document.querySelector('input[name=data]');
        const hiddenTheme = document.querySelector('input[name=theme]');
        const tpl = @json($templates->map(fn($t) => ['id' => $t->id, 'defaults' => $t->defaults])->values());

        function updatePreview(data, theme) {
            // contoh minimal; tambahkan key lain sesuai renderer kamu
            const $pv = document.getElementById('label-preview');
            const qs = sel => $pv.querySelector(sel);

            if (qs('[data-key=title]')) qs('[data-key=title]').textContent = (data.title || '').toUpperCase();
            if (qs('[data-key=ing]')) qs('[data-key=ing]').textContent = data.ing || '';
            if (qs('[data-key=store]')) qs('[data-key=store]').textContent = data.store || '';

            // update CSS variables theme jika kamu pakai di renderer
            $pv.style.setProperty('--green', theme?.green || '#2e7d32');
            $pv.style.setProperty('--amber', theme?.amber || '#f59e0b');
            $pv.style.setProperty('--paper', theme?.paper || '#ffffff');
        }

        radios.forEach(r => r.addEventListener('change', e => {
            const t = tpl.find(x => String(x.id) === e.target.value);
            if (!t || !t.defaults) return;

            const defs = t.defaults; // sudah object, no JSON.parse
            hiddenData.value = JSON.stringify(defs.keys || {});
            hiddenTheme.value = JSON.stringify(defs.theme || {});
            updatePreview(defs.keys || {}, defs.theme || {});
        }));
    </script>
</x-app-layout>
