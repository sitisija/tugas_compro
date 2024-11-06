<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services;
class ServiceController extends Controller
{
    public function index(){
        $services=Services::get();
        return view('backend.layanan.index',[
            'services'=>$services,
        ]);
    }
    public function tambah(){
        return view('backend.layanan.tambah');
    }
    
    public function aksi_tambah(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            // 'slug' => Str::slug($request->title),
            'created_at' =>date('Y-m-d h:i:s'),
        ];

        Services::insert($data);
        return redirect()->route('backend.service')->with('success', 'service berhasil ditambahkan');
    }
    public function edit($id){
        $services = Services::where('id',$id)->first();
        return view('backend.layanan.edit', compact('services'));
    }
    public function aksi_edit(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'created_at' =>date('Y-m-d h:i:s')
        ];
    
        Services::where('id',$id)->update($data);
    
        return redirect()->route('backend.service');
    }
    
}

