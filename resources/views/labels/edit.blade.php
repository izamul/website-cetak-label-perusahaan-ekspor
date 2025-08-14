<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Label – {{ $label->title }}</h2>
    </x-slot>

    <style>
        .editor-wrap {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 24px;
            align-items: start;
        }

        .panel {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .04);
            box-sizing: border-box;
        }

        .panel h3 {
            font-weight: 700;
            margin: 0 0 10px;
        }

        .fg {
            margin-bottom: 10px;
        }

        .fg label {
            display: block;
            font-size: 12px;
            color: #64748B;
            margin-bottom: 6px;
        }

        .fg input[type=text],
        .fg textarea {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .theme-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .colorbox {
            width: 100%;
            height: 40px;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 0;
        }

        .action-row {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }

        .btn {
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-save {
            background: #166534;
            color: #fff;
        }

        .btn-print {
            border-color: #166534;
            color: #166534;
            background: transparent;
        }

        .badges-up {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 12px;
        }

        .badge-card {
            border: 1px dashed #CBD5E1;
            border-radius: 12px;
            padding: 10px;
            text-align: center;
            background: #fff;
        }

        .badge-card input {
            width: 100%;
        }

        .preview-panel {
            background: #F8FAFC;
            border: 1px dashed #E2E8F0;
            border-radius: 16px;
            padding: 16px;
            min-height: 200px;
            box-sizing: border-box;
        }

        .preview-panel .print-stage {
            display: flex;
            justify-content: center;
        }

        @media (max-width: 1024px) {
            .editor-wrap {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- Alerts --}}
    @if (session('ok'))
        <div class="panel" style="border-color:#DCFCE7; background:#F0FDF4; color:#166534;">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="panel" style="border-color:#FEE2E2; background:#FEF2F2; color:#991B1B;">
            @foreach ($errors->all() as $e)
                <div>• {{ $e }}</div>
            @endforeach
        </div>
    @endif

    <div class="editor-wrap">
        {{-- LEFT: CONTROLS (form utama) --}}
        <form id="editorForm" method="POST" action="{{ route('labels.update', $label) }}" class="panel"
            enctype="multipart/form-data">
            @csrf @method('PUT')

            <h3>Label Controls</h3>

            <div class="fg">
                <label>Label Title</label>
                <input id="f_title" type="text" value="{{ $label->title }}">
            </div>

            <div class="grid-2">
                <div class="fg"><label>Lot No.</label> <input id="f_lot" type="text"
                        value="{{ $label->data['lot'] ?? '-' }}"></div>
                <div class="fg"><label>Lot Code Supplier</label> <input id="f_lcs" type="text"
                        value="{{ $label->data['lcs'] ?? '-' }}"></div>
                <div class="fg"><label>Production Date</label> <input id="f_prod" type="text"
                        value="{{ $label->data['prod'] ?? '-' }}"></div>
                <div class="fg"><label>Best Before</label> <input id="f_best" type="text"
                        value="{{ $label->data['best'] ?? '-' }}"></div>
            </div>

            <div class="fg"><label>Ingredient</label> <input id="f_ing" type="text"
                    value="{{ $label->data['ing'] ?? '' }}"></div>
            <div class="fg"><label>Net Weight</label> <input id="f_weight" type="text"
                    value="{{ $label->data['weight'] ?? '' }}"></div>

            <div class="fg"><label>Imported By</label>
                <textarea id="f_imported" rows="3">{{ $label->data['imported'] ?? '' }}</textarea>
            </div>

            <div class="fg"><label>Manufactured By</label>
                <textarea id="f_manufactured" rows="3">{{ $label->data['manufactured'] ?? '' }}</textarea>
            </div>

            <div class="fg"><label>Attributes / Notes (box label)</label>
                <input id="f_attributeBox" type="text"
                    value="{{ $label->data['attributeBox'] ?? 'ATTRIBUTES / NOTES' }}">
            </div>

            <div class="fg"><label>Storage Note</label>
                <input id="f_store" type="text" value="{{ $label->data['store'] ?? '' }}">
            </div>

            <div class="fg"><label>Export Note</label>
                <input id="f_export" type="text" value="{{ $label->data['export'] ?? '' }}">
            </div>

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

            {{-- BADGE UI (tidak ada <form> di sini) --}}
            <div class="fg"><label>Badges</label>
                <div class="badges-up">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="badge-card">
                            <div style="font-size:12px;color:#64748B;margin-bottom:6px;">Badge {{ $i }}
                            </div>
                            <input type="file" name="file" accept="image/*" form="badgeForm{{ $i }}">
                            <button class="btn" style="margin-top:6px; background:#111827; color:#fff;"
                                form="badgeForm{{ $i }}">Upload</button>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Hidden payloads untuk submit (update) --}}
            <input type="hidden" name="title" id="h_title">
            <input type="hidden" name="data" id="h_data">
            <input type="hidden" name="theme" id="h_theme">

            <div class="action-row">
                <button class="btn btn-save" type="submit">Save</button>
                <a class="btn btn-print" href="{{ route('labels.print', $label) }}" target="_blank">Print</a>
            </div>
        </form>

        {{-- RIGHT: PREVIEW --}}
        <div class="preview-panel">
            @include('labels.partials.renderer', ['label' => $label])
        </div>
    </div>

    {{-- ====== 4 FORM UPLOAD (di luar form utama; tidak mengganggu layout/grid) ====== --}}
    @for ($i = 1; $i <= 4; $i++)
        <form id="badgeForm{{ $i }}" action="{{ route('labels.assets.store', [$label, $i]) }}"
            method="POST" enctype="multipart/form-data" style="display:none">
            @csrf
        </form>
    @endfor

    <script>
        // --- state dari server (gunakan (object)[] agar aman di PHP/Blade)
        const initData = @json($label->data ?? (object) []);
        const initTheme = @json($label->theme ?? (object) []);
        let state = {
            title: @json($label->title),
            data: {
                ...initData
            },
            theme: {
                ...{
                    green: '#2e7d32',
                    amber: '#f59e0b',
                    paper: '#ffffff'
                },
                ...initTheme
            }
        };

        const pv = document.querySelector('.print-stage'); // container preview

        // helper update DOM preview
        function setText(key, val) {
            const el = document.querySelector(`[data-key="${key}"]`);
            if (el) el.textContent = (key === 'title' ? String(val || '').toUpperCase() : (val ?? ''));
        }

        function setThemeVars(t) {
            pv?.style.setProperty('--green', t.green || '#2e7d32');
            pv?.style.setProperty('--amber', t.amber || '#f59e0b');
            pv?.style.setProperty('--paper', t.paper || '#ffffff');
        }

        // seed awal
        setText('organic', state.data.organic ?? 'Organic');
        setText('title', state.data.title ?? 'COCONUT SUGAR');
        ['lot', 'lcs', 'prod', 'best', 'ing', 'weight', 'imported', 'manufactured', 'attributeBox', 'store', 'export']
        .forEach(k => setText(k, state.data[k] ?? (['lot', 'lcs', 'prod', 'best'].includes(k) ? '-' : '')));
        setThemeVars(state.theme);

        // bind inputs -> state -> preview
        const bind = (id, key, isTheme = false) => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('input', (e) => {
                const v = e.target.value;
                if (key === 'title') {
                    state.title = v;
                    setText('title', v);
                } else if (isTheme) {
                    state.theme[key] = v;
                    setThemeVars(state.theme);
                } else {
                    state.data[key] = v;
                    setText(key, v);
                }
            });
        };

        bind('f_title', 'title');
        bind('f_lot', 'lot');
        bind('f_lcs', 'lcs');
        bind('f_prod', 'prod');
        bind('f_best', 'best');
        bind('f_ing', 'ing');
        bind('f_weight', 'weight');
        bind('f_imported', 'imported');
        bind('f_manufactured', 'manufactured');
        bind('f_attributeBox', 'attributeBox');
        bind('f_store', 'store');
        bind('f_export', 'export');
        bind('t_green', 'green', true);
        bind('t_amber', 'amber', true);
        bind('t_paper', 'paper', true);

        // sebelum submit, kirim payload JSON
        document.getElementById('editorForm').addEventListener('submit', () => {
            document.getElementById('h_title').value = state.title;
            document.getElementById('h_data').value = JSON.stringify(state.data);
            document.getElementById('h_theme').value = JSON.stringify(state.theme);
        });
    </script>
</x-app-layout>
