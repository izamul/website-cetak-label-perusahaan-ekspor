@php
    $w = $label->template->width_cm;
    $h = $label->template->height_cm;
    $data = collect($label->data);
    $theme = $label->theme; // ['green','amber','paper']
@endphp

<style>
    .print-stage {
        --green: {{ $theme['green'] }};
        --amber: {{ $theme['amber'] }};
        --paper: {{ $theme['paper'] }};
    }

    .sheet {
        width: {{ $w }}cm;
        height: {{ $h }}cm;
        background: var(--paper);
        border: 1px solid #d1d5db;
        border-radius: .5cm;
        padding: .8cm;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .06);
    }

    .head {
        border: 2px solid var(--green);
        border-radius: .4cm;
        text-align: center;
        padding: .3cm;
    }

    .title {
        font-weight: 800;
        letter-spacing: .04cm;
        font-size: .9cm;
    }

    .subtle {
        color: #6b7280;
        font-style: italic;
    }

    .row {
        display: grid;
        grid-template-columns: 4.5cm .5cm 1fr;
        gap: .15cm;
        margin-top: .12cm;
    }

    .label {
        font-weight: 700;
        text-transform: uppercase;
    }

    .block {
        border: 2px solid var(--green);
        border-radius: .3cm;
        padding: .3cm;
        margin-top: .2cm;
    }

    .block-title {
        font-weight: 800;
    }

    .badges {
        display: grid;
        grid-template-columns: repeat(4, 1fr) 2fr;
        gap: .2cm;
        margin-top: .2cm;
    }

    .badge {
        border: 1px dashed #d1d5db;
        min-height: 1.5cm;
        display: grid;
        place-items: center;
    }

    .badge img {
        max-height: 1.2cm;
        max-width: 100%;
    }

    .notice {
        border: 2px solid var(--amber);
        border-radius: .2cm;
        padding: .2cm;
        font-weight: 700;
        text-transform: uppercase;
    }

    .muted {
        color: #6b7280;
        font-size: .3cm;
    }

    @media print {
        .sheet {
            box-shadow: none;
        }
    }
</style>

<div class="print-stage">
    <div class="sheet">
        <div class="head">
            <div class="subtle">{{ $data['organic'] ?? 'Organic' }}</div>
            <div class="title">{{ strtoupper($data['title'] ?? 'COCONUT SUGAR') }}</div>
        </div>

        <div class="row">
            <div class="label">Lot No.</div>
            <div>:</div>
            <div>{{ $data['lot'] ?? '–' }}</div>
        </div>
        <div class="row">
            <div class="label">Lot Code Supplier</div>
            <div>:</div>
            <div>{{ $data['lcs'] ?? '–' }}</div>
        </div>
        <div class="row">
            <div class="label">Production Date</div>
            <div>:</div>
            <div>{{ $data['prod'] ?? '—' }}</div>
        </div>
        <div class="row">
            <div class="label">Best Before</div>
            <div>:</div>
            <div>{{ $data['best'] ?? '—' }}</div>
        </div>
        <div class="row">
            <div class="label">Ingredient</div>
            <div>:</div>
            <div>{{ $data['ing'] ?? '' }}</div>
        </div>
        <div class="row">
            <div class="label">Net. Weight</div>
            <div>:</div>
            <div>{{ $data['weight'] ?? '' }}</div>
        </div>

        <div class="block">
            <div class="block-title">IMPORTED BY :</div>
            <div style="white-space:pre-wrap">{{ $data['imported'] ?? '' }}</div>
        </div>

        <div class="block">
            <div class="block-title">MANUFACTURED BY :</div>
            <div style="white-space:pre-wrap">{{ $data['manufactured'] ?? '' }}</div>
        </div>

        <div class="badges">
            @for ($i = 1; $i <= 4; $i++)
                <div class="badge">
                    @if ($src = $label->badgeSrc($i))
                        <img src="{{ $src }}" alt="badge {{ $i }}">
                    @else
                        <span class="muted">Badge {{ $i }}</span>
                    @endif
                </div>
            @endfor
            <div class="notice">{{ $data['attributeBox'] ?? 'ATTRIBUTES / NOTES' }}</div>
        </div>

        <div class="notice">{{ $data['store'] ?? '' }}</div>
        <div class="muted">{{ $data['export'] ?? '' }}</div>
    </div>
</div>
