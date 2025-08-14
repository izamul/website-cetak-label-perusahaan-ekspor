<?php

namespace App\Http\Controllers;

use App\Models\{Label, LabelTemplate};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LabelController extends Controller
{
    public function index(){
        $labels = Label::where('user_id', Auth::id())->latest()->with('template')->paginate(12);
        return view('labels.index', compact('labels'));
    }

    public function create()
    {
        $templates = LabelTemplate::all();
        $template = $templates->firstOrFail();

        // Pakai cast dari model LabelTemplate; fallback kalau ternyata masih string
        $defaults = is_array($template->defaults)
            ? $template->defaults
            : (json_decode($template->defaults, true) ?? []);

        // Dummy Label (instance model, bukan stdClass)
        $label = new \App\Models\Label([
            'title' => 'Untitled Label',
            'data'  => $defaults['keys']  ?? [],
            'theme' => $defaults['theme'] ?? [],
        ]);

        // Set relasi biar $label->template & $label->assets bisa dipakai
        $label->setRelation('template', $template);
        $label->setRelation('assets', collect()); // kosong dulu → badgeSrc() tetap aman

        return view('labels.create', compact('templates', 'label'));
    }


    public function store(Request $r)
    {
        // Terima sebagai string, bukan array
        $validated = $r->validate([
            'label_template_id' => 'required|exists:label_templates,id',
            'title'             => 'required|string|max:120',
            'data'              => 'required',   // <- string JSON
            'theme'             => 'required',   // <- string JSON
        ]);

        // Decode aman
        $dataArray  = json_decode($validated['data'], true);
        $themeArray = json_decode($validated['theme'], true);

        if (! is_array($dataArray) || ! is_array($themeArray)) {
            return back()
                ->withErrors(['data' => 'Payload data/theme tidak valid.'])
                ->withInput();
        }

        $label = Label::create([
            'user_id'           => auth()->id(),
            'label_template_id' => $validated['label_template_id'],
            'title'             => $validated['title'],
            'data'              => $dataArray,
            'theme'             => $themeArray,
        ]);

        return redirect()->route('labels.edit', $label)->with('ok', 'Label created');
    }


    public function edit(Label $label){
        $this->authorize('update', $label);
        $label->load('template','assets');
        return view('labels.edit', compact('label'));
    }

    public function update(Request $r, Label $label)
    {
        $this->authorize('update', $label);

        $validated = $r->validate([
            'title' => 'required|string|max:120',
            'data'  => 'required', // string JSON
            'theme' => 'required', // string JSON
        ]);

        $data  = json_decode($validated['data'], true);
        $theme = json_decode($validated['theme'], true);

        if (!is_array($data) || !is_array($theme)) {
            return back()->withErrors(['data' => 'Payload data/theme tidak valid.'])->withInput();
        }

        $label->update([
            'title' => $validated['title'],
            'data'  => $data,
            'theme' => $theme,
        ]);

        return back()->with('ok','Saved');
}


    public function show(Label $label){
        $this->authorize('view', $label);
        $label->load('template','assets');
        return view('labels.show', compact('label'));
    }

    public function print(Label $label){
        $this->authorize('view', $label);
        $label->load('template','assets');
        return view('labels.print', compact('label'));
    }

    public function pdf(Label $label){ // requires barryvdh/laravel-dompdf
        $this->authorize('view', $label);
        $label->load('template','assets');
        $pdf = \PDF::loadView('labels.print', compact('label'))
            ->setPaper([0,0,$label->template->width_cm*28.3465,$label->template->height_cm*28.3465]);
        return $pdf->download('label-'.$label->id.'.pdf');
    }

    public function destroy(Label $label){
        $this->authorize('delete', $label);
        $label->delete();
        return redirect()->route('labels.index')->with('ok','Deleted');
    }
}