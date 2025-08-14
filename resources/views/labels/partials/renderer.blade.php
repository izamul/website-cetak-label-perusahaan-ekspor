@php
  // Template size (fallback kalau belum ada)
  $tpl = $label->template ?? (object)['width_cm' => 13, 'height_cm' => 14];
  $w = $tpl->width_cm;
  $h = $tpl->height_cm;

  // Normalisasi data & theme (kasih default yang aman)
  $data  = collect(is_array($label->data ?? null)  ? $label->data  : []);
  $theme =       (is_array($label->theme ?? null) ? $label->theme : []);
  $theme = array_merge(['green'=>'#2e7d32','amber'=>'#f59e0b','paper'=>'#fff'], $theme);

  // Helper aman untuk badgeSrc saat label dummy (tanpa assets)
  $canBadge = isset($label) && method_exists($label, 'badgeSrc');
@endphp

<style>
  .print-stage {
    --green: {{ $theme['green'] ?? '#00b050' }};
    --amber: {{ $theme['amber'] }};
    --paper: {{ $theme['paper'] }};
  }
  .label-sheet {
    width: {{ $w }}cm; height: {{ $h }}cm;
    background: var(--paper);
    border-radius: .5cm;
    padding: .8cm;
    box-shadow: 0 6px 20px rgba(0,0,0,.06);
    border: 1px solid #E5E7EB;
    box-sizing: border-box;
    font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, "Helvetica Neue", Arial, "Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
  }
  .head { border: 2px solid var(--green); border-radius: .4cm; text-align:center; padding:.35cm .5cm .4cm; }
  .head .topline { color:#64748B; font-style: italic; font-size:.42cm; line-height:1; margin-bottom:.15cm; }
  .head .title { font-weight:800; letter-spacing:.04cm; font-size:.95cm; line-height:1.1; }

  .row { display:grid; grid-template-columns: 4.6cm .5cm 1fr; gap:.18cm; margin-top:.16cm; align-items:baseline; }
  .term { font-weight:800; text-transform:uppercase; font-size:.42cm; }
  .sep { color:#6B7280; font-weight:700; }
  .val { font-size:.42cm; }

  .panel { border:2px solid var(--green); border-radius:.35cm; padding:.35cm; margin-top:.28cm; }
  .panel .title { font-weight:900; text-transform:uppercase; font-size:.44cm; margin-bottom:.15cm; }
  .panel .multiline { white-space:pre-wrap; font-size:.42cm; }

  .badge-grid { display:grid; grid-template-columns: repeat(4, 2.2cm) 1fr; gap:.25cm; margin-top:.25cm; align-items:stretch; }
  .badge { border:2px dashed #CBD5E1; min-height:2.0cm; border-radius:.3cm; display:grid; place-items:center; background:#fff; }
  .badge img { max-height:1.6cm; max-width:100%; display:block; }

  .notice { border:2px solid var(--amber); border-radius:.3cm; padding:.28cm .35cm; font-weight:800; text-transform:uppercase; font-size:.42cm; }

  .foot-store { margin-top:.35cm; }
  .foot-export { color:#64748B; font-size:.36cm; margin-top:.12cm; }

  @media print { .label-sheet{ box-shadow:none; } }
</style>

<div class="print-stage">
  <div class="label-sheet">
    <div class="head">
      <div class="topline" data-key="organic">{{ $data['organic'] ?? 'Organic' }}</div>
      <div class="title"   data-key="title">{{ strtoupper($data['title'] ?? 'COCONUT SUGAR') }}</div>
    </div>

    <div class="row"><div class="term">LOT NO.</div><div class="sep">:</div><div class="val" data-key="lot">{{ $data['lot'] ?? '–' }}</div></div>
    <div class="row"><div class="term">LOT CODE SUPPLIER</div><div class="sep">:</div><div class="val" data-key="lcs">{{ $data['lcs'] ?? '–' }}</div></div>
    <div class="row"><div class="term">PRODUCTION DATE</div><div class="sep">:</div><div class="val" data-key="prod">{{ $data['prod'] ?? '–' }}</div></div>
    <div class="row"><div class="term">BEST BEFORE</div><div class="sep">:</div><div class="val" data-key="best">{{ $data['best'] ?? '–' }}</div></div>
    <div class="row"><div class="term">INGREDIENT</div><div class="sep">:</div><div class="val" data-key="ing">{{ $data['ing'] ?? '' }}</div></div>
    <div class="row"><div class="term">NET. WEIGHT</div><div class="sep">:</div><div class="val" data-key="weight">{{ $data['weight'] ?? '' }}</div></div>

    <div class="panel">
      <div class="title">IMPORTED BY :</div>
      <div class="multiline" data-key="imported">{{ $data['imported'] ?? '' }}</div>
    </div>

    <div class="panel">
      <div class="title">MANUFACTURED BY :</div>
      <div class="multiline" data-key="manufactured">{{ $data['manufactured'] ?? '' }}</div>
    </div>

    <div class="badge-grid">
      @for ($i=1; $i<=4; $i++)
        <div class="badge">
          @if ($canBadge && ($src = $label->badgeSrc($i)))
            <img src="{{ $src }}" alt="badge {{ $i }}">
          @else
            <span style="color:#94A3B8; font-size:.36cm;">Badge {{ $i }}</span>
          @endif
        </div>
      @endfor
      <div class="notice" data-key="attributeBox">{{ $data['attributeBox'] ?? 'ATTRIBUTES / NOTES' }}</div>
    </div>

    <div class="foot-store notice" data-key="store">{{ $data['store'] ?? '' }}</div>
    <div class="foot-export" data-key="export">{{ $data['export'] ?? '' }}</div>
  </div>
</div>
