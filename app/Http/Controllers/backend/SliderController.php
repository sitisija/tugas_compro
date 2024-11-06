<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sliders;
Use App\Models\Blogs;
use App\Models\Services;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index(){
        $blogs=Blogs::get();
        $sliders=Sliders::get();
        $services=Services::get();
        // compact('sliders')
        return view('backend.sliders.index', [
            'sliders'=>$sliders,
            'blogs'=>$blogs,
            'services'=>$services
        ]);
    }
    public function tambah()
    {
        // Menampilkan form untuk menambah blog
        return view('backend.sliders.tambah');
    }
    public function aksi_tambah(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'file' => 'required|file|mimes:jpeg,png|max:2048',
        ]);
    
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            // 'slug' => Str::slug($request->title),
            'created_at' =>date('Y-m-d h:i:s')
        ];
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('sliders'), $filename);
            $data['file'] = 'sliders/' . $filename;
        }
    
        Sliders::insert($data);
        return redirect()->route('backend.slider')->with('success', 'slider berhasil ditambahkan');
    }
    public function hapus($id){
        $ambilDataSlider=Sliders::where('id',$id)->first();
        Sliders::where('id',$id)->delete();
        $this->hapus_gambar($ambilDataSlider->file);
        return redirect()->back();
    }
    protected function hapus_gambar($gambar){
        if (file_exists($gambar)) {
            unlink($gambar);
        }
    }
    public function edit($id){
        $sliders = Sliders::where('id',$id)->first();
        return view('backend.sliders.edit', compact('sliders'));
    }
    public function aksi_edit(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'file' => 'required|file|mimes:jpeg,png|max:2048',
        ]);
    
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'created_at' =>date('Y-m-d h:i:s')
        ];
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('slider'), $filename);
            $data['file'] = 'sliders/' . $filename;
            $ambilDataSlider=Sliders::where('id',$id)->first();
            $this->hapus_gambar($ambilDataSlider->file);
        }
    
        Sliders::where('id',$id)->update($data);
    
        return redirect()->route('backend.slider');
    }
}

