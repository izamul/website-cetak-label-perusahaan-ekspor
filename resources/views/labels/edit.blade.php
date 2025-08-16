{{-- resources/views/labels/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Label – {{ $label->title }}</h2>
    </x-slot>

    @php
        $tpl = $label->template;
        $codeLower = strtolower((string) ($tpl->code ?? ''));
        $isLandscape = ($tpl->width_cm ?? 0) > ($tpl->height_cm ?? 0);
        // SAMA dengan create: anchor kalau code=anchor ATAU landscape
        $isAnchor = $codeLower === 'anchor' || $isLandscape;
    @endphp

    <style>
        :root {
            --card-radius: 16px;
            --inner-radius: 12px
        }

        .page-wrap {
            max-width: 1280px;
            margin: 0 auto
        }

        .editor-wrap {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 24px;
            align-items: start
        }

        .left-stack {
            display: flex;
            flex-direction: column;
            gap: 24px
        }

        .panel {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: var(--card-radius);
            padding: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .04);
            box-sizing: border-box
        }

        .panel h3 {
            font-weight: 700;
            margin: 0 0 10px
        }

        .fg {
            margin-bottom: 10px
        }

        .fg label {
            display: block;
            font-size: 12px;
            color: #64748B;
            margin-bottom: 6px
        }

        .fg input[type=text],
        .fg textarea {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
            box-sizing: border-box
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px
        }

        .theme-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px
        }

        .colorbox {
            width: 100%;
            height: 40px;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 0
        }

        .action-row {
            display: flex;
            gap: 10px;
            margin-top: 12px
        }

        .btn {
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            cursor: pointer;
            font-weight: 600
        }

        .btn-save {
            background: #166534;
            color: #fff
        }

        .btn-print {
            border-color: #166534;
            color: #166534;
            background: transparent
        }

        .badges-up {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 8px
        }

        .badge-card {
            border: 1px dashed #CBD5E1;
            border-radius: var(--inner-radius);
            background: #fff;
            padding: 12px;
            display: grid;
            grid-template-rows: auto 1fr auto;
            gap: 10px
        }

        .badge-title {
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            text-align: center
        }

        .badge-preview {
            aspect-ratio: 1/1;
            border: 1px dashed #E5E7EB;
            border-radius: 10px;
            background: #F8FAFC;
            display: grid;
            place-items: center;
            overflow: hidden
        }

        .badge-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain
        }

        .badge-empty {
            font-size: 12px;
            color: #9CA3AF
        }

        .badge-actions {
            display: flex;
            gap: 8px;
            justify-content: space-between;
            align-items: center
        }

        .badge-file {
            display: none
        }

        .btn-dark {
            background: #111827;
            color: #fff;
            border-radius: 10px;
            padding: 8px 12px
        }

        .btn-ghost {
            background: #fff;
            color: #6B7280;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 8px 12px
        }

        .btn-dark:hover {
            background: #0b1220
        }

        .btn-ghost:hover {
            background: #F9FAFB
        }

        .preview-panel {
            background: #F8FAFC;
            border: 1px dashed #E2E8F0;
            border-radius: var(--card-radius);
            padding: 0;
            min-height: 200px;
            box-sizing: border-box
        }

        .preview-toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 10px 12px;
            border-bottom: 1px dashed #E2E8F0
        }

        .preview-body {
            padding: 16px;
            display: flex;
            justify-content: center;
            overflow: auto
        }

        .zoom-info {
            font-size: 12px;
            color: #64748B;
            min-width: 110px;
            text-align: right
        }

        input[type=range] {
            width: 220px
        }

        @media (max-width:1024px) {
            .editor-wrap {
                grid-template-columns: 1fr
            }
        }
    </style>

    <div class="page-wrap" style="margin-top:2em">
        @if (session('ok'))
            <div class="panel" style="border-color:#DCFCE7;background:#F0FDF4;color:#166534;margin-bottom:12px;">
                {{ session('ok') }}</div>
        @endif
        @if ($errors->any())
            <div class="panel" style="border-color:#FEE2E2;background:#FEF2F2;color:#991B1B;margin-bottom:12px;">
                @foreach ($errors->all() as $e)
                    <div>• {{ $e }}</div>
                @endforeach
            </div>
        @endif

        <div class="editor-wrap">
            <div class="left-stack">
                <form id="editorForm" method="POST" action="{{ route('labels.update', $label) }}" class="panel">
                    @csrf @method('PUT')
                    <h3>Label Controls</h3>

                    <div class="fg"><label>Label Title</label><input id="f_title" type="text"
                            value="{{ $label->title }}"></div>

                    @if (!$isAnchor)
                        {{-- ===== Default fields ===== --}}
                        <div class="grid-2">
                            <div class="fg"><label>Lot No.</label><input id="f_lot" type="text"
                                    value="{{ $label->data['lot'] ?? '-' }}"></div>
                            <div class="fg"><label>Lot Code Supplier</label><input id="f_lcs" type="text"
                                    value="{{ $label->data['lot_code_supplier'] ?? ($label->data['lcs'] ?? '-') }}">
                            </div>
                            <div class="fg"><label>Production Date</label><input id="f_prod" type="text"
                                    value="{{ $label->data['prod'] ?? '-' }}"></div>
                            <div class="fg"><label>Best Before</label><input id="f_best" type="text"
                                    value="{{ $label->data['best'] ?? '-' }}"></div>
                        </div>
                        <div class="fg"><label>Ingredient</label><input id="f_ing" type="text"
                                value="{{ $label->data['ing'] ?? '' }}"></div>
                        <div class="fg"><label>Net Weight</label><input id="f_weight" type="text"
                                value="{{ $label->data['weight'] ?? '' }}"></div>
                        <div class="fg"><label>Imported By</label>
                            <textarea id="f_imported" rows="3">{{ $label->data['imported'] ?? '' }}</textarea>
                        </div>
                        <div class="fg"><label>Manufactured By</label>
                            <textarea id="f_manufactured" rows="3">{{ $label->data['manufactured'] ?? '' }}</textarea>
                        </div>
                        <div class="fg"><label>Attributes / Notes</label><input id="f_attributeBox" type="text"
                                value="{{ $label->data['attributeBox'] ?? 'ATTRIBUTES / NOTES' }}"></div>
                        <div class="fg"><label>Storage Note</label><input id="f_store" type="text"
                                value="{{ $label->data['store'] ?? '' }}"></div>
                        <div class="fg"><label>Export Note</label><input id="f_export" type="text"
                                value="{{ $label->data['export'] ?? '' }}"></div>
                    @else
                        {{-- ===== Anchor fields ===== --}}
                        <div class="grid-2">
                            <div class="fg"><label>Item No.</label><input id="f_item_no" type="text"
                                    value="{{ $label->data['item_no'] ?? '' }}"></div>
                            <div class="fg"><label>Origin</label><input id="f_origin" type="text"
                                    value="{{ $label->data['origin'] ?? '' }}"></div>
                        </div>
                        <div class="grid-2">
                            <div class="fg"><label>Net Weight (KG)</label><input id="f_netkg" type="text"
                                    value="{{ $label->data['net_kg'] ?? '' }}"></div>
                            <div class="fg"><label>Net Weight (LBS)</label><input id="f_netl" type="text"
                                    value="{{ $label->data['net_lbs'] ?? '' }}"></div>
                        </div>
                        <div class="grid-2">
                            <div class="fg"><label>Production Date</label><input id="f_prod_date" type="text"
                                    value="{{ $label->data['prod_date'] ?? '' }}"></div>
                            <div class="fg"><label>Lot Number</label><input id="f_lot_no" type="text"
                                    value="{{ $label->data['lot_no'] ?? '' }}"></div>
                        </div>
                        <div class="fg"><label>Ingredients</label><input id="f_ingredients" type="text"
                                value="{{ $label->data['ingredients'] ?? '' }}"></div>
                        <div class="fg"><label>Manufactured By</label>
                            <textarea id="f_manufactured" rows="3">{{ $label->data['manufactured'] ?? '' }}</textarea>
                        </div>
                        <div class="fg"><label>Packed For</label>
                            <textarea id="f_packed_for" rows="3">{{ $label->data['packed_for'] ?? '' }}</textarea>
                        </div>
                        <div class="fg"><label>Contact / Address (Right)</label>
                            <textarea id="f_contact" rows="5">{{ $label->data['contact'] ?? '' }}</textarea>
                        </div>
                    @endif

                    <div class="fg"><label>Theme</label>
                        <div class="theme-grid">
                            <div><small>Green</small><input id="t_green" class="colorbox" type="color"
                                    value="{{ $label->theme['green'] ?? '#2e7d32' }}"></div>
                            <div><small>Yellow-Orange</small><input id="t_amber" class="colorbox" type="color"
                                    value="{{ $label->theme['amber'] ?? '#f59e0b' }}"></div>
                            <div><small>Paper</small><input id="t_paper" class="colorbox" type="color"
                                    value="{{ $label->theme['paper'] ?? '#ffffff' }}"></div>
                        </div>
                    </div>

                    <input type="hidden" name="title" id="h_title">
                    <input type="hidden" name="data" id="h_data">
                    <input type="hidden" name="theme" id="h_theme">

                    <div class="action-row">
                        <button class="btn btn-save" type="submit">Save</button>
                        <a class="btn btn-print" href="{{ route('labels.print', $label) }}"
                            target="_blank">Print</a>
                    </div>
                </form>

                <div class="panel">
                    <h3>Badges</h3>
                    <div class="badges-up">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="badge-card">
                                <div class="badge-title">Badge {{ $i }}</div>
                                <div class="badge-preview">
                                    @if ($src = $label->badgeSrc($i))
                                        <img src="{{ $src }}" alt="badge {{ $i }}">
                                    @else
                                        <span class="badge-empty">No image</span>
                                    @endif
                                </div>
                                <div class="badge-actions">
                                    <form method="POST" action="{{ route('labels.assets.store', [$label, $i]) }}"
                                        enctype="multipart/form-data">@csrf
                                        <input class="badge-file" type="file" name="file" accept="image/*"
                                            onchange="this.form.submit()">
                                        <button type="button" class="btn-dark"
                                            onclick="this.previousElementSibling.click()">Upload</button>
                                    </form>
                                    @if ($label->badgeSrc($i))
                                        <form method="POST"
                                            action="{{ route('labels.assets.destroy', [$label, $i]) }}"
                                            onsubmit="return confirm('Remove badge {{ $i }}?')">@csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-ghost">Remove</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- RIGHT: PREVIEW --}}
            <div class="preview-panel">
                <div class="preview-toolbar">
                    <div class="zoom-info"><span id="zoomPct">100%</span></div>
                    <input type="range" id="zoom" min="60" max="140" value="100">
                </div>
                <div class="preview-body">
                    @php
                        $renderer = $isAnchor ? 'labels.partials.renderer_anchor' : 'labels.partials.renderer';
                    @endphp
                    @include($renderer, ['label' => $label])
                </div>
            </div>
        </div>
    </div>

    <script>
        // === Mode template (SAMA dgn create)
        const IS_ANCHOR = @json($isAnchor);

        // === State dari server (aman)
        const initData = @json($label->data ?? (object) []);
        const initTheme = @json($label->theme ?? (object) []);

        let state = {
            title: @json($label->title),
            data: {
                ...initData
            },
            theme: {
                green: '#2e7d32',
                amber: '#f59e0b',
                paper: '#ffffff',
                ...initTheme
            }
        };

        const stage = document.querySelector('.preview-body .print-stage');
        const q = (sel, root = document) => root.querySelector(sel);

        const applyText = (key, v) => {
            const targets = [key];
            if (key === 'lcs') targets.push('lot_code_supplier');
            if (key === 'lot_code_supplier') targets.push('lcs');
            targets.forEach(k => {
                const el = q(`[data-key="${k}"]`);
                if (el) el.textContent = (k === 'title' ? String(v || '').toUpperCase() : (v ?? ''));
            });
        };
        const applyTheme = (t) => {
            stage?.style.setProperty('--green', t.green || '#2e7d32');
            stage?.style.setProperty('--amber', t.amber || '#f59e0b');
            stage?.style.setProperty('--paper', t.paper || '#ffffff');
        };

        function seedPreview() {
            if (!IS_ANCHOR) {
                applyText('organic', state.data.organic ?? 'Organic');
                applyText('title', state.data.title ?? 'COCONUT SUGAR');
                ['lot', 'lot_code_supplier', 'prod', 'best', 'ing', 'weight', 'imported', 'manufactured', 'attributeBox',
                    'store', 'export'
                ]
                .forEach(k => applyText(k, state.data[k] ?? (['lot', 'lot_code_supplier', 'prod', 'best'].includes(k) ?
                    '–' : '')));
            } else {
                applyText('title', state.data.title ?? 'COCONUT SUGAR ORGANIC');
                applyText('item_no', state.data.item_no ?? 'ITEM NO – COCO17');
                applyText('net_kg', state.data.net_kg ?? '25 KGS');
                applyText('net_lbs', state.data.net_lbs ?? '(55.11 LBS)');
                applyText('ingredients', state.data.ingredients ?? 'Organic Coconut Sugar');
                applyText('prod_date', state.data.prod_date ?? '');
                applyText('lot_no', state.data.lot_no ?? '');
                applyText('origin', state.data.origin ?? 'INDONESIA');
                applyText('manufactured', state.data.manufactured ?? '');
                applyText('packed_for', state.data.packed_for ?? '');
                applyText('contact', state.data.contact ?? '');
            }
            applyTheme(state.theme);
        }

        function bind(id, key, isTheme = false) {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('input', e => {
                const v = e.target.value;
                if (key === 'title') {
                    state.title = v;
                    applyText('title', v);
                } else if (isTheme) {
                    state.theme[key] = v;
                    applyTheme(state.theme);
                } else {
                    state.data[key] = v;
                    applyText(key, v);
                }
            });
        }

        if (!IS_ANCHOR) {
            bind('f_title', 'title');
            bind('f_lot', 'lot');
            bind('f_lcs', 'lot_code_supplier');
            bind('f_prod', 'prod');
            bind('f_best', 'best');
            bind('f_ing', 'ing');
            bind('f_weight', 'weight');
            bind('f_imported', 'imported');
            bind('f_manufactured', 'manufactured');
            bind('f_attributeBox', 'attributeBox');
            bind('f_store', 'store');
            bind('f_export', 'export');
        } else {
            bind('f_title', 'title');
            bind('f_item_no', 'item_no');
            bind('f_netkg', 'net_kg');
            bind('f_netl', 'net_lbs');
            bind('f_ingredients', 'ingredients');
            bind('f_prod_date', 'prod_date');
            bind('f_lot_no', 'lot_no');
            bind('f_origin', 'origin');
            bind('f_manufactured', 'manufactured');
            bind('f_packed_for', 'packed_for');
            bind('f_contact', 'contact');
        }
        bind('t_green', 'green', true);
        bind('t_amber', 'amber', true);
        bind('t_paper', 'paper', true);

        seedPreview();

        document.getElementById('editorForm').addEventListener('submit', () => {
            document.getElementById('h_title').value = state.title;
            document.getElementById('h_data').value = JSON.stringify(state.data);
            document.getElementById('h_theme').value = JSON.stringify(state.theme);
        });

        const zoom = document.getElementById('zoom'),
            zoomPct = document.getElementById('zoomPct');
        const setZoom = (p) => {
            stage?.style.setProperty('--zoom', (p / 100).toFixed(2));
            zoomPct.textContent = `${p}%`;
        };
        zoom.addEventListener('input', e => setZoom(e.target.value));
        setZoom(100);
    </script>
</x-app-layout>
