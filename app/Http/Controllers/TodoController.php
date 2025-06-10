<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthManager;
use PHPUnit\Framework\Constraint\FiluseeExists;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $data = Todo::query()
            ->where('user_id', $user->id)
            ->when($request->filled('search'), function($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10);
            // ->get();

        return response()->json([
            'success'=>true,
            'message'=>'Berhasil mengambil data',
            'data'=> $data,
        ]);

        // return new TodoResource(true, 'Berhasil mengambil data!', $data);
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
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'description'   => 'required',
            'status' =>'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $imgName = null;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imgName = 'todo/' . $image->hashName();
            $image->move(public_path('todo'), basename($imgName));
        }

        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'image' => $imgName,
            'user_id' => auth('api')->id()
        ]);

        return new TodoResource(true, 'Data berhasil ditambahkan', $todo);

        // $image = $request->file('image');
        // if($image) {
        //     $imgName = $image->hashName();
        //     $image->move(public_path('todo'), $imgName);
        // }

        // $create = Todo::create([
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'image' => $imgName
        // ]);

        // return new TodoResource(true, 'Data berhasil ditambahkan', $create);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = auth('api')->user();

        $todo = Todo::where('id', $id)
                    ->where('user_id', $user->id)
                    ->first();

        if (!$todo) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan milik user ini',
                'data' => null
             ], 404);
         }

        return new TodoResource(true, 'Data berhasil ditemukan', $todo);
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

        //cek apakah hanya field status yg dikirim
        if ($request->has('status') && !$request->has('title')&& !$request->has('description')) {
            $validator = Validator::make($request->all(),[
                'status' => 'required'
            ]);

            if ($validator->fails()){
                return response()->json($validator->errors(), 422);
            }

            $todo->update([
                'status' => $request->status
            ]);

            return new TodoResource(true, 'Status berhasil diubah', $todo->fresh());
        }

        // return new TodoResource(true, 'Data berhasil diubah', $request->input('title'));
        // define validation rules
        $validator = Validator::make($request->all(), [
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'description'   => 'required',
            'status' => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $imgName = $todo->image;

        if($request->hasFile('image')){
            $newImage = $request->file('image');

            //hapus gambar lama
            if ($imgName && File::exists(public_path($imgName))){
                File::delete(public_path($imgName));
            }

            //simpan gambar baru
            $imgName = 'todo/' . $newImage->hashName();
            $newImage->move(public_path('todo'), basename($imgName));
        }

        $todo->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'status'=>$request->status,
            'image'=>$imgName,
        ]);

        return new TodoResource(true, 'Data berhasil diubah', $todo->fresh());

        // $newImage = $request->file('image');

        // if ($newImage) {
        //     $oldImagePath = public_path('todo/' . $todo->image);

        //     // Hapus file lama jika ada
        //     if (File::exists($oldImagePath)) {
        //         File::delete($oldImagePath);
        //     }

        //     // Simpan file baru
        //     $newImageName = $newImage->hashName();
        //     $newImage->move(public_path('todo'), $newImageName);
        // }

        // $todo->update([
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'status' => $request->status,
        //     'image' => $newImageName
        // ]);

        // $todo->fresh();

        // return new TodoResource(true, 'Data berhasil diubah', $todo);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Todo $todo)
    {
        if ($todo->image && File::exists(public_path($todo->image))){
            File::delete(public_path($todo->image));
        }

        $todo->delete();

        return response()->json([
        'success' => true,
        'message' => 'Data telah dihapus',
        'data' => null,
        ], 200);
    }
    // public function destroy(Todo $todo)
    // {
    //     $imagePath  = $todo->image;
    //     $path = public_path('todo/' . $imagePath );
    //     if($imagePath  && File::exists($path)) {
    //         File::delete($path);
    //     }

    //     $todo->delete();

    //     return new TodoResource(true, 'Data telah dihapus!', null);
    // }
}
