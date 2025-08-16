@php
    // ukuran kertas dari template
    $w = $label->template->width_cm ?? 13;
    $h = $label->template->height_cm ?? 14;

    // data & tema (fallback aman)
    $d = collect($label->data ?? []);
    $t = collect($label->theme ?? ['green' => '#2e7d32', 'amber' => '#f59e0b', 'paper' => '#ffffff']);

    // helper badge
    $badgeSrc = function (int $slot) use ($label) {
        if (method_exists($label, 'badgeSrc')) {
            return $label->badgeSrc($slot);
        }
        return null;
    };
@endphp

<style>
    .print-stage {
        --green: {{ $t['green'] ?? '#2e7d32' }};
        --amber: {{ $t['amber'] ?? '#f59e0b' }};
        --paper: {{ $t['paper'] ?? '#ffffff' }};
        --zoom: 1;
        /* 1 knob buat kontrol rapat/longgar */
        --space: .15cm;
        /* << ubah sekali kalau mau lebih rapat/longgar */
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
    }

    .sheet13x14 {
        width: {{ $w }}cm;
        height: {{ $h }}cm;
        transform: scale(var(--zoom));
        transform-origin: top left;
        background: var(--paper);
        color: #111827;
        border: 1px solid #D1D5DB;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .06);
        padding: .90cm;
        /* was: 1.2cm */
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        gap: calc(var(--space) * .9);
        /* was: .35cm */
    }

    /* header (tetap center) */
    .hdr {
        text-align: center;
        margin-bottom: calc(var(--space) * .6);
    }

    .hdr .organic {
        font-style: italic;
        color: #1f2937;
        font-size: .40cm;
        line-height: 1;
        margin-bottom: calc(var(--space) * .35);
    }

    .hdr .title {
        font-weight: 800;
        letter-spacing: .04cm;
        font-size: .60cm;
        text-transform: uppercase;
    }

    /* garis pemisah + margin rapat */
    .rule {
        border-bottom: 2px solid #111827;
        opacity: .85;
        margin: calc(var(--space) * .55) 0;
    }

    .rule.light {
        border-bottom: 1px solid #9CA3AF;
        opacity: .7;
        margin: calc(var(--space) * .45) 0;
    }

    /* key:value list – ":" dekat label */
    .kv {
        display: grid;
        grid-template-columns: max-content .10cm 1fr;
        /* was .12cm */
        column-gap: .10cm;
        row-gap: calc(var(--space) * .5);
        /* was .12cm */
        font-size: .23cm;
    }

    .kv .k {
        font-weight: 800;
        text-transform: uppercase;
    }

    .kv .v {
        font-weight: 500;
    }

    .kv .v.muted {
        color: #4B5563;
        font-weight: 400;
    }

    /* block judul + isi */
    .block {
        display: grid;
        row-gap: calc(var(--space) * .45);
        /* was .12cm */
        font-size: .215cm;
    }

    .block .tt {
        font-weight: 800;
        text-transform: uppercase;
    }

    .block .body {
        white-space: pre-wrap;
        line-height: 1.18;
    }

    /* rapat dikit */

    /* area bawah */
    .bottom {
        margin-top: calc(var(--space) * 2);
        display: grid;
        grid-template-columns: 1fr 4.0cm;
        /* sedikit dipersempit */
        column-gap: .10cm;
        align-items: end;
    }

    /* badges */
    .badges {
        display: grid;
        grid-template-columns: repeat(4, .95cm);
        /* was 1cm */
        gap: .12cm;
        /* was .2cm */
        margin-bottom: calc(var(--space) * .4);
        /* was .25cm */
    }

    .badge {
        height: .95cm;
        border: 1px dashed #D1D5DB;
        border-radius: .12cm;
        display: grid;
        place-items: center;
        overflow: hidden;
        background: #fff;
    }

    .badge img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* catatan bawah */
    .notes {
        font-size: .215cm;
        line-height: 1.18;
    }

    /* barcode */
    .barcode {
        border: 1px dashed #9CA3AF;
        border-radius: .12cm;
        height: 1.8cm;
        /* was 2cm */
        display: grid;
        place-items: center;
        color: #6B7280;
        font-size: .20cm;
        background: #fff;
    }
</style>

<div class="print-stage">
    <div class="sheet13x14">

        {{-- HEADER --}}
        <div class="hdr">
            <div class="organic" data-key="organic">{{ $d['organic'] ?? 'Organic' }}</div>
            <div class="title" data-key="title">{{ strtoupper($d['title'] ?? 'COCONUT SUGAR') }}</div>
        </div>

        <div class="kv">
            <div class="k">LOT NO.</div>
            <div>:</div>
            <div class="v" data-key="lot">{{ $d['lot'] ?? '-' }}</div>
        </div>

        <div class="rule"></div>

        {{-- KEY:VALUE LIST --}}
        <div class="kv">
            <div class="k">LOT CODE SUPPLIER</div>
            <div>:</div>
            <div class="v" data-key="lot_code_supplier">{{ $d['lot_code_supplier'] ?? '-' }}</div>

            <div class="k">Production Date</div>
            <div>:</div>
            <div class="v" data-key="prod">{{ $d['prod'] ?? '-' }}</div>

            <div class="k">Best Before Date</div>
            <div>:</div>
            <div class="v" data-key="best">{{ $d['best'] ?? '-' }}</div>

            <div class="k">Production Code</div>
            <div>:</div>
            <div class="v" data-key="pcode">{{ $d['pcode'] ?? '-' }}</div>

            <div class="k">Ingredient</div>
            <div>:</div>
            <div class="v" data-key="ing">{{ $d['ing'] ?? 'ORGANIC COCONUT NECTAR' }}</div>
        </div>

        <div class="rule"></div>

        <div class="kv">
            <div class="k">Net Wt.</div>
            <div>:</div>
            <div class="v" data-key="weight">{{ $d['weight'] ?? '20kg (4×5kg) / 44.1 lbs (4×11 lbs)' }}</div>
        </div>

        <div class="rule"></div>

        {{-- IMPORTED BY (BODY DI BAWAH) --}}
        <div class="block">
            <div class="tt">IMPORTED BY</div>
            <div class="body" data-key="imported">{{ $d['imported'] ?? "Company\nStreet\nCity, ZIP\nCountry" }}</div>
        </div>

        <div class="rule"></div>
        <div class="block">
            <div class="tt">MANUFACTURED BY</div>
            <div class="body" data-key="manufactured">
                {{ $d['manufactured'] ?? "Company\nStreet\nCity, ZIP\nCountry" }}</div>
        </div>

        {{-- BOTTOM AREA --}}
        <div class="bottom">
            <div>
                <div class="badges">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="badge">
                            @if ($src = $badgeSrc($i))
                                <img src="{{ $src }}" alt="badge {{ $i }}">
                            @else
                                <span style="font-size:.35cm;color:#9CA3AF">Badge {{ $i }}</span>
                            @endif
                        </div>
                    @endfor
                </div>

                <div class="notes">
                    <div data-key="store">{{ $d['store'] ?? 'STORE IN A COOL AND DRY PLACE' }}</div>
                    <div class="export" data-key="export">{{ $d['export'] ?? '* FOR EXPORT ONLY *' }}</div>
                </div>
            </div>

            <div class="barcode">
                {{-- taruh barcode di sini (nanti diganti img/renderer barcode) --}}
                BARCODE AREA
            </div>
        </div>

    </div>
</div>
