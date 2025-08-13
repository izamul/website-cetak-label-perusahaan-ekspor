<?php

namespace App\Http\Controllers;
use App\Models\{Label, LabelAsset};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabelAssetController extends Controller
{
    public function store(Request $r, Label $label, int $slot){
        $this->authorize('update', $label);
        $r->validate(['file'=>'required|image|max:2048']);
        $path = $r->file('file')->store("labels/{$label->id}", 'public');
        LabelAsset::updateOrCreate(
            ['label_id'=>$label->id,'slot'=>$slot],
            ['path'=>$path]
        );
        return back()->with('ok','Badge updated');
    }

    public function destroy(Label $label, int $slot){
        $this->authorize('update', $label);
        if($asset = $label->assets()->where('slot',$slot)->first()){
            Storage::disk('public')->delete($asset->path);
            $asset->delete();
        }
        return back()->with('ok','Badge removed');
    }
}
