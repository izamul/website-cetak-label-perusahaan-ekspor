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

    public function create(){
        $templates = LabelTemplate::all();
        return view('labels.create', compact('templates'));
    }

    public function store(Request $r){
        $data = $r->validate([
            'label_template_id' => 'required|exists:label_templates,id',
            'title' => 'required|string|max:120',
            'data' => 'required|array',
            'theme' => 'required|array',
        ]);
        $label = Label::create($data + ['user_id'=>Auth::id()]);
        return redirect()->route('labels.edit',$label)->with('ok','Label created');
    }

    public function edit(Label $label){
        $this->authorize('update', $label);
        $label->load('template','assets');
        return view('labels.edit', compact('label'));
    }

    public function update(Request $r, Label $label){
        $this->authorize('update', $label);
        $data = $r->validate([
            'title' => 'required|string|max:120',
            'data' => 'required|array',
            'theme' => 'required|array',
        ]);
        $label->update($data);
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