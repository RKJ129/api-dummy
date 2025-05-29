<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\FileExists;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Todo::all();
        return new TodoResource(true, 'Berhasil mengambil data!', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'description'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        if($image) {
            $imgName = $image->hashName();
            $image->move(public_path('todo'), $imgName);
        }

        $create = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imgName
        ]);

        return new TodoResource(true, 'Data berhasil ditambahkan', $create);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        // return new TodoResource(true, 'Data berhasil diubah', $request->input('title'));
        // define validation rules
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'description'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $newImage = $request->file('image');

        if ($newImage) {
            $oldImagePath = public_path('todo/' . $todo->image);

            // Hapus file lama jika ada
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            // Simpan file baru
            $newImageName = $newImage->hashName();
            $newImage->move(public_path('todo'), $newImageName);
        }

        $update = $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $newImageName
        ]);

        return new TodoResource(true, 'Data berhasil diubah', $update);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $imagePath  = $todo->image;
        $path = public_path('todo/' . $imagePath );
        if($imagePath  && File::exists($path)) {
            File::delete($path);
        }

        $todo->delete();

        return new TodoResource(true, 'Data telah dihapus!', null);
    }
}
