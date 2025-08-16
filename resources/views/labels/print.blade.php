{{-- resources/views/labels/print.blade.php --}}
@php
    /** @var \App\Models\Label $label */
    $tpl = $label->template;
    $codeLower   = strtolower((string) ($tpl->code ?? ''));
    $isLandscape = ($tpl->width_cm ?? 0) > ($tpl->height_cm ?? 0);

    // >>> Sama persis seperti di create/edit/show:
    $renderer = ($codeLower === 'anchor' || $isLandscape)
        ? 'labels.partials.renderer_anchor'
        : 'labels.partials.renderer';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Print — {{ $label->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Minimal screen/print styles --}}
    <style>
        :root { --bg: #f8fafc; }
        html,body{margin:0;padding:0}
        body{background:var(--bg);font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial}
        .wrap{max-width:1100px;margin:0 auto;padding:16px}
        .toolbar{
            display:flex;gap:8px;align-items:center;margin:8px 0 16px;
        }
        .btn{
            display:inline-flex;align-items:center;gap:.5rem;
            padding:.55rem 1rem;border-radius:.65rem;font-weight:700;text-decoration:none;
            border:1px solid #D1D5DB;background:#fff;color:#374151;transition:.15s ease;
        }
        .btn:hover{background:#F9FAFB}
        .btn-primary{background:#16a34a;color:#fff;border-color:#15803d}
        .btn-primary:hover{background:#15803d}
        .btn svg{width:1rem;height:1rem}

        /* Rapikan area render saat print */
        .print-stage{ --zoom: 1 !important; }        /* pastikan skala 1:1 */
        .print-stage > .sheet13x14,
        .print-stage > .sheet-anchor{
            box-shadow:none !important;               /* hilangkan shadow di print */
            border:0 !important;
        }

        @media print {
            body{background:#fff}
            .wrap{padding:0}
            .toolbar{display:none !important}        /* toolbar disembunyikan */
        }
    </style>
</head>
<body>
    <div class="wrap">
        {{-- Toolbar untuk layar saja --}}
        <div class="toolbar">
            <a href="{{ url()->previous() ?: route('labels.index') }}" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7 7-7M3 12h18"/></svg>
                Back
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M6 9V4h12v5h2a2 2 0 0 1 2 2v5h-4v4H8v-4H4v-5a2 2 0 0 1 2-2h0zm2 10h8v-4H8v4zm8-10V6H8v3h8z"/></svg>
                Print
            </button>
        </div>

        {{-- RENDERER (Anchor atau Default) --}}
        @include($renderer, ['label' => $label])
    </div>
</body>
</html>
