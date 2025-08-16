{{-- resources/views/labels/partials/renderer_anchor.blade.php --}}
@php
    // Ukuran (landscape 14.5 × 10.0 cm)
    $w = $label->template->width_cm ?? 14.5;
    $h = $label->template->height_cm ?? 10.0;

    // Data & tema
    $d = collect($label->data ?? []);
    $t = collect($label->theme ?? ['paper' => '#ffffff']);

    // Keys (fallback aman)
    $title = strtoupper($d['title'] ?? 'COCONUT SUGAR ORGANIC');
    $itemNo = $d['item_no'] ?? 'ITEM NO – COCO17';
    $netKg = $d['net_kg'] ?? '25 KGS';
    $netLbs = $d['net_lbs'] ?? '(55.11 LBS)';

    $ingredients = $d['ingredients'] ?? 'Organic Coconut Sugar';
    $prodDate = $d['prod_date'] ?? '';
    $lotNo = $d['lot_no'] ?? '';
    $origin = $d['origin'] ?? 'INDONESIA';
    $mfgBy = $d['manufactured'] ?? 'PT. INTRAFOOD SINGABERA INDONESIA, SUKOHARJO 57552, INDONESIA';

    $packedFor = $d['packed_for'] ?? '';
    $contact =
        $d['contact'] ??
        "4876 Rocking Horse Circle S.\nFargo, North Dakota 58104\nP | 701.499.1480\nF | 701.499.1481\ninfo@anchoringredients.com\nwww.anchoringredients.com";

    // Badge helper (pakai badge #1 sebagai logo jika ada)
    $badgeSrc = function (int $slot) use ($label) {
        return method_exists($label, 'badgeSrc') ? $label->badgeSrc($slot) : null;
    };
@endphp

<style>
    .print-stage {
        --paper: {{ $t['paper'] ?? '#ffffff' }};
        --zoom: 1;
        /* knob spacing biar seragam dengan renderer 13x14 */
        --space: .15cm;
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
        color: #111827;
    }

    .sheet-anchor {
        width: {{ $w }}cm;
        height: {{ $h }}cm;
        transform: scale(var(--zoom));
        transform-origin: top left;
        background: var(--paper);
        border: 1px solid #D1D5DB;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .06);
        padding: .90cm;
        /* disamakan dengan 13x14 */
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        gap: calc(var(--space) * .9);
    }

    .sheet-anchor .frame {
        border: 1.2px solid #111827;
        border-radius: 2px;
        /* set 0 kalau mau benar-benar lancip */
    }

    .sheet-anchor .frame+.frame {
        margin-top: calc(var(--space) * .6);
    }

    /* ===================== HEADER ===================== */
    /* 3 kolom; judul span 2 kiri, net weight kanan */
    .a-header {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        /* total 3 col, judul span 2 */
        align-items: center;
        min-height: 2.0cm;
        padding: calc(var(--space) * .9) calc(var(--space) * 1.4);
    }

    .a-header>div {
        padding: 0 calc(var(--space) * .9);
        height: 100%;
        display: flex;
        align-items: center;
    }

    .a-header .c1 {
        grid-column: 1 / span 2;
    }

    .a-title {
        text-align: left;
    }

    .a-title h1 {
        margin: 0;
        font-size: .60cm;
        /* = title di 13x14 */
        letter-spacing: .04cm;
        font-weight: 800;
    }

    .a-title .sub {
        margin-top: calc(var(--space) * .25);
        font-size: .23cm;
        /* = key-value font */
        letter-spacing: .02cm;
    }

    .a-header .c3 {
        grid-column: 3 / span 1;
        border-left: 1.2px solid #111827;
        justify-content: center;
        text-align: center;
    }

    .a-net-label {
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .02cm;
        font-size: .23cm;
        /* diseragamkan */
        margin-bottom: calc(var(--space) * .2);
    }

    .a-net-value {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: calc(var(--space) * .2);
    }

    .a-net-value .kg {
        font-weight: 800;
        font-size: .40cm;
        /* setara 'organic' di 13x14 */
        letter-spacing: .02cm;
        line-height: 1.05;
    }

    .a-net-value .lbs {
        font-size: .215cm;
        /* body */
        color: #4B5563;
        line-height: 1.1;
    }

    /* ===================== DETAILS ===================== */
    .a-details {
        text-align: center;
        padding: calc(var(--space) * 1.6) calc(var(--space) * 2.4);
        line-height: 1.30;
    }

    .a-row {
        margin: calc(var(--space) * .2) 0;
        font-size: .23cm;
        /* diseragamkan */
    }

    .a-row .k {
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .02cm;
    }

    .a-row .v {
        font-weight: 500;
    }

    .a-row.blank .v {
        color: #9CA3AF;
        font-weight: 400;
    }

    /* ===================== FOOTER ===================== */
    .a-footer {
        display: grid;
        grid-template-columns: 22% 56% 22%;
        align-items: center;
        min-height: 2.6cm;
        padding: calc(var(--space) * 1.0) calc(var(--space) * 1.2);
    }

    .a-footer>div {
        padding: 0 calc(var(--space) * .8);
    }

    .a-packed .label {
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .02cm;
        font-size: .215cm;
        /* body (judul kecil) */
    }

    .a-packed .value {
        margin-top: calc(var(--space) * .4);
        white-space: pre-wrap;
        font-size: .215cm;
        line-height: 1.18;
    }

    .a-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .a-brand .logo {
        max-height: 2.0cm;
        /* sedikit disesuaikan agar seimbang dengan skala baru */
        max-width: 100%;
        object-fit: contain;
    }

    .a-brand .word {
        font-weight: 800;
        letter-spacing: .02cm;
    }

    .a-brand .word .big {
        font-size: .50cm;
        display: block;
    }

    .a-brand .word .small {
        font-size: .30cm;
        color: #991B1B;
        display: block;
    }

    .a-contact {
        white-space: pre-wrap;
        font-size: .215cm;
        /* body */
        line-height: 1.20;
    }
</style>

<div class="print-stage">
    <div class="sheet-anchor">
        {{-- ===== HEADER ===== --}}
        <div class="frame a-header">
            {{-- kiri: judul (span 2 kolom) --}}
            <div class="c1 a-title">
                <div>
                    <h1 data-key="title">{{ $title }}</h1>
                    <div class="sub" data-key="item_no">{{ $itemNo }}</div>
                </div>
            </div>

            {{-- kanan: Net Weight (kolom 3) --}}
            <div class="c3">
                <div>
                    <div class="a-net-label">NET WEIGHT:</div>
                    <div class="a-net-value">
                        <div class="kg" data-key="net_kg">{{ $netKg }}</div>
                        <div class="lbs" data-key="net_lbs">{{ $netLbs }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== DETAILS (merge 3 kolom, center) ===== --}}
        <div class="frame a-details">
            <div class="a-row"><span class="k">INGREDIENTS: </span><span class="v"
                    data-key="ingredients">{{ $ingredients }}</span></div>
            <div class="a-row blank"><span class="k">PRODUCTION DATE: </span><span class="v"
                    data-key="prod_date">{{ $prodDate }}</span></div>
            <div class="a-row blank"><span class="k">LOT NUMBER: </span><span class="v"
                    data-key="lot_no">{{ $lotNo }}</span></div>
            <div class="a-row"><span class="k">ORIGIN: </span><span class="v"
                    data-key="origin">{{ $origin }}</span></div>
            <div class="a-row"><span class="k">MANUFACTURED BY: </span><span class="v"
                    data-key="manufactured">{{ $mfgBy }}</span></div>
        </div>

        {{-- ===== FOOTER ===== --}}
        <div class="frame a-footer">
            <div class="a-packed">
                <div class="label">PACKED FOR:</div>
                <div class="value" data-key="packed_for">{{ $packedFor }}</div>
            </div>

            <div class="a-brand">
                @if ($badgeSrc(1))
                    <img class="logo" src="{{ $badgeSrc(1) }}" alt="Brand">
                @else
                    <div class="word">
                        <span class="big">ANCHOR</span>
                        <span class="small">INGREDIENTS</span>
                    </div>
                @endif
            </div>

            <div class="a-contact" data-key="contact">{{ $contact }}</div>
        </div>
    </div>
</div>
