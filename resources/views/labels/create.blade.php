{{-- resources/views/labels/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-900">Create Label</h2>
            <a href="{{ route('labels.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
        </div>
    </x-slot>

    @php
        /** @var \Illuminate\Support\Collection|\App\Models\LabelTemplate[] $templates */
        $first = $templates->first();
        $def = (array) ($first->defaults ?? []);
        $keys = (array) ($def['keys'] ?? []);
        $theme = (array) ($def['theme'] ?? ['green' => '#2e7d32', 'amber' => '#f59e0b', 'paper' => '#ffffff']);

        // payload ringkas untuk JS
        $tplPayload = $templates
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'width_cm' => $t->width_cm,
                    'height_cm' => $t->height_cm,
                    'orientation' => $t->orientation,
                    'code' => $t->code ?? null,
                    'defaults' => $t->defaults, // ['keys'=>..., 'theme'=>...]
                ];
            })
            ->values();
    @endphp

    <style>
        .page-wrap {
            max-width: 1280px;
            margin: 0 auto
        }

        .grid-wrap {
            display: grid;
            grid-template-columns: minmax(260px, 420px) 1fr;
            gap: 24px;
            align-items: start
        }

        .left-col {
            position: sticky;
            top: 16px;
            height: fit-content
        }

        .panel {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .04)
        }

        .panel h3 {
            font-weight: 700;
            margin: 0 0 12px
        }

        .template-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            background: #F8FAFC;
            cursor: pointer
        }

        .template-card:hover {
            background: #F1F5F9
        }

        .template-card input {
            accent-color: #16a34a
        }

        .template-meta {
            font-size: 12px;
            color: #64748B;
            margin-top: 2px
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem 1rem;
            border-radius: .75rem;
            background: #16a34a;
            color: #fff;
            font-weight: 700;
            border: 1px solid #15803d
        }

        .btn-primary:hover {
            background: #15803d
        }

        .preview-panel {
            background: #F8FAFC;
            border: 1px dashed #E2E8F0;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            height: clamp(420px, 78vh, 84vh);
            min-height: 420px
        }

        .preview-toolbar {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-bottom: 1px dashed #E2E8F0
        }

        .toolbar-right {
            display: flex;
            gap: 8px;
            align-items: center
        }

        .btn-tool {
            border: 1px solid #E5E7EB;
            background: #fff;
            border-radius: 10px;
            padding: 6px 10px;
            font-weight: 600;
            color: #374151
        }

        .btn-tool[aria-pressed="true"] {
            background: #111827;
            color: #fff;
            border-color: #111827
        }

        .preview-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: auto;
            padding: 16px
        }

        .print-stage {
            outline: none !important
        }

        input[type=range] {
            width: 220px
        }

        @media (max-width:1024px) {
            .grid-wrap {
                grid-template-columns: 1fr
            }
        }
    </style>

    <div class="page-wrap px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid-wrap">

            {{-- LEFT: pilih template + submit --}}
            <div class="left-col">
                <form method="POST" action="{{ route('labels.store') }}" class="panel">
                    @csrf
                    <h3>Pilih Template</h3>

                    <div class="space-y-3">
                        @foreach ($templates as $tpl)
                            <label class="template-card">
                                <input type="radio" name="label_template_id" value="{{ $tpl->id }}"
                                    {{ $loop->first ? 'checked' : '' }}>
                                <div>
                                    <div class="font-semibold">{{ $tpl->name }}</div>
                                    <div class="template-meta">
                                        {{ number_format($tpl->width_cm, 1) }} × {{ number_format($tpl->height_cm, 1) }}
                                        cm ({{ $tpl->orientation }})
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <input type="hidden" name="title" value="Untitled Label">
                    {{-- Akan di-overwrite JS saat user ganti template --}}
                    <input type="hidden" name="data" id="h_data" value='@json($keys)'>
                    <input type="hidden" name="theme" id="h_theme" value='@json($theme)'>

                    <div class="mt-4">
                        <button class="btn-primary" type="submit">Create</button>
                    </div>
                </form>
            </div>

            {{-- RIGHT: preview --}}
            <div class="preview-panel">
                <div class="preview-toolbar">
                    <div class="text-sm text-gray-600">
                        <span id="sizeInfo">{{ number_format($first->width_cm, 1) }} ×
                            {{ number_format($first->height_cm, 1) }} cm ({{ $first->orientation }})</span>
                    </div>
                    <div class="toolbar-right">
                        <button type="button" class="btn-tool" id="fitW" aria-pressed="true">Fit width</button>
                        <button type="button" class="btn-tool" id="fitH" aria-pressed="false">Fit height</button>
                        <button type="button" class="btn-tool" id="oneToOne" aria-pressed="false">100%</button>
                        <span class="text-xs text-gray-500" id="zoomPct">100%</span>
                        <input type="range" id="zoom" min="30" max="200" value="100">
                    </div>
                </div>
                <div class="preview-body">
                    {{-- Satu container saja; kita mount template ke sini --}}
                    <div id="pv"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- Bank renderer: TARUH DI <template> supaya tidak dirender --}}
    @foreach ($templates as $tpl)
        @php
            $defT = (array) ($tpl->defaults ?? []);
            $keysT = (array) ($defT['keys'] ?? []);
            $themeT = (array) ($defT['theme'] ?? []);
            $dummy = (object) ['template' => $tpl, 'data' => $keysT, 'theme' => $themeT];

            $rc = strtolower((string) ($tpl->code ?? ''));
            $rendererT =
                $rc === 'anchor'
                    ? 'labels.partials.renderer_anchor'
                    : (($tpl->width_cm ?? 0) > ($tpl->height_cm ?? 0)
                        ? 'labels.partials.renderer_anchor'
                        : 'labels.partials.renderer');
        @endphp

        <template id="tpl-{{ $tpl->id }}">
            @include($rendererT, ['label' => $dummy])
        </template>
    @endforeach


    {{-- JSON payload untuk JS --}}
    <script id="tpls-json" type="application/json">{!! $tplPayload->toJson(JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!}</script>

    <script>
        const TPLS = JSON.parse(document.getElementById('tpls-json').textContent);
        const radios = document.querySelectorAll('input[name=label_template_id]');
        const hData = document.getElementById('h_data');
        const hTheme = document.getElementById('h_theme');
        const sizeEl = document.getElementById('sizeInfo');
        const pv = document.getElementById('pv');

        const zoomIn = document.getElementById('zoom');
        const zoomPct = document.getElementById('zoomPct');
        const fitWBtn = document.getElementById('fitW');
        const fitHBtn = document.getElementById('fitH');
        const oneBtn = document.getElementById('oneToOne');

        let stage = null; // .print-stage hasil mount
        let fitMode = 'fitW'; // default auto: fit width

        function setStage() {
            stage = pv.querySelector('.print-stage') || null;
        }

        function setZoom(pct) {
            const v = Math.max(10, Math.min(300, Number(pct) || 100));
            zoomIn.value = Math.round(v);
            zoomPct.textContent = `${Math.round(v)}%`;
            stage?.style.setProperty('--zoom', (v / 100).toFixed(3));
        }

        function toggleFitBtns() {
            fitWBtn.setAttribute('aria-pressed', String(fitMode === 'fitW'));
            fitHBtn.setAttribute('aria-pressed', String(fitMode === 'fitH'));
            oneBtn.setAttribute('aria-pressed', String(fitMode === 'manual' && Number(zoomIn.value) === 100));
        }

        function sheetEl() {
            return stage?.firstElementChild || null;
        }

        function baseSizePx() {
            const el = sheetEl();
            return el ? {
                w: el.offsetWidth,
                h: el.offsetHeight
            } : {
                w: 1,
                h: 1
            };
        }

        function fitWidth() {
            const {
                w
            } = baseSizePx();
            const avail = pv.closest('.preview-body').clientWidth - 32;
            const pct = Math.max(10, Math.min(300, (avail / w) * 100));
            fitMode = 'fitW';
            toggleFitBtns();
            setZoom(pct);
        }

        function fitHeight() {
            const {
                h
            } = baseSizePx();
            const avail = pv.closest('.preview-body').clientHeight - 32;
            const pct = Math.max(10, Math.min(300, (avail / h) * 100));
            fitMode = 'fitH';
            toggleFitBtns();
            setZoom(pct);
        }

        function reflowByMode() {
            if (fitMode === 'fitW') fitWidth();
            else if (fitMode === 'fitH') fitHeight();
        }

        function setThemeVars(t = {}) {
            stage?.style.setProperty('--green', t.green || '#2e7d32');
            stage?.style.setProperty('--amber', t.amber || '#f59e0b');
            stage?.style.setProperty('--paper', t.paper || '#ffffff');
        }

        function setKey(k, v) {
            const el = pv.querySelector(`[data-key="${k}"]`);
            if (el) el.textContent = (k === 'title') ? String(v || '').toUpperCase() : (v ?? '');
        }

        function applyDefaults(def = {}) {
            const keys = def.keys || {};
            const theme = def.theme || {};
            hData.value = JSON.stringify(keys);
            hTheme.value = JSON.stringify(theme);

            const alias = {
                lcs: 'lot_code_supplier'
            };
            const wanted = ['organic', 'title', 'lot', 'lot_code_supplier', 'lcs', 'prod', 'best', 'pcode', 'ing', 'weight',
                'imported', 'manufactured', 'attributeBox', 'store', 'export'
            ];
            wanted.forEach(k => {
                const src = (k in keys) ? k : (alias[k] && alias[k] in keys ? alias[k] : null);
                if (src) setKey(k, keys[src]);
            });

            setThemeVars(theme);
            reflowByMode();
        }

        function mount(tplId) {
            const t = document.getElementById('tpl-' + tplId);
            if (!t) return;
            // simpan zoom saat ini kalau manual
            const prevZoom = Number(zoomIn.value) || 100;

            pv.innerHTML = t.innerHTML; // render ulang isi template
            setStage();

            // reset zoom sesuai mode
            if (fitMode === 'manual') setZoom(prevZoom);
            else reflowByMode();
        }

        // events
        zoomIn.addEventListener('input', e => {
            fitMode = 'manual';
            toggleFitBtns();
            setZoom(e.target.value);
        });
        fitWBtn.addEventListener('click', fitWidth);
        fitHBtn.addEventListener('click', fitHeight);
        oneBtn.addEventListener('click', () => {
            fitMode = 'manual';
            toggleFitBtns();
            setZoom(100);
        });
        window.addEventListener('resize', () => {
            if (fitMode !== 'manual') reflowByMode();
        });

        radios.forEach(r => r.addEventListener('change', e => {
            const t = TPLS.find(x => String(x.id) === String(e.target.value));
            if (!t) return;
            sizeEl.textContent =
                `${Number(t.width_cm).toFixed(1)} × ${Number(t.height_cm).toFixed(1)} cm (${t.orientation})`;
            mount(t.id); // ganti renderer
            applyDefaults(t.defaults || {});
        }));

        // boot
        (function boot() {
            toggleFitBtns();
            const checked = document.querySelector('input[name=label_template_id]:checked') || radios[0];
            const t0 = TPLS.find(x => String(x.id) === String(checked?.value));
            if (t0) {
                mount(t0.id);
                applyDefaults(t0.defaults || {});
                // default auto-fit width
                fitWidth();
            }
        })();
    </script>
</x-app-layout>
