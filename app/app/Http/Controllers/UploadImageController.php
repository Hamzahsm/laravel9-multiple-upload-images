<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Photo;

class UploadImageController extends Controller
{
    //
    public function index()
    {
        return view('upload'); 
    }
    public function store(Request $request)
    {
        
        $validateImageData = $request->validate([
        'images' => 'required',
        'images.*' => 'mimes:jpg,png,jpeg,gif,svg', 
        'image' => 'required',
        'judul' => 'required',
        'slug' => 'required'
        ]);
        if($request->hasfile('images')) 
        {
            foreach($request->file('images') as $key => $file)
            {
                $path = $file->store('public/images'); 
                $name = $file->getClientOriginalName();
                $insert[$key]['title'] = $name;
                $insert[$key]['path'] = $path;
                // $insert = $request->hasfile('image');

                // these code below is work for single image
                $insert[$key]['image'] = $request->hasfile('image'); 

                // these code below is work for judl dan slug , jadi kesimpulannya kalau mau nambah field utnuk diupload di db, nambahnya lewat sini
                $insert[$key]['judul'] = $validateImageData['judul'];
                $insert[$key]['slug'] = $validateImageData['slug'];
            }
        }

        if($request->file('image')) {
            // these code below work disimpan ke folder foto produk (storage)
            $validateImageData['image'] = $request->file('image')->store('foto-produk'); 
        }

        Photo::insert($insert);

        return redirect('upload-multiple-image-preview')->with('status', 'All Images has been uploaded successfully');
    }
}
