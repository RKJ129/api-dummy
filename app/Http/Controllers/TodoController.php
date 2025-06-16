<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoResource;
use App\Models\Image;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use PHPUnit\Framework\Constraint\FiluseeExists;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $data = Todo::with(['user', 'images'])
            ->when($request->filled('mine') && $request->mine == 'true', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            // ->where('user_id', $user->id)
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
        // Validasi
        $validator = Validator::make($request->all(), [
            'image'       => 'nullable',
            'image.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'       => 'required',
            'description' => 'required',
            'status'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        DB::beginTransaction();
        try {
            $todo = Todo::create([
                'title'       => $request->title,
                'description' => $request->description,
                'status'      => $request->status,
                // 'image'       => json_encode($imgNames),
                'user_id'     => auth('api')->id(),
            ]);

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $img) {
                    $imgName = time() . '_' .  $img->hashName();
                    $img->move(public_path('todo'), $imgName);
                    Image::create([
                        'todo_id' => $todo->id,
                        'image' => $imgName
                    ]);
                }
            }
            
            DB::commit();
            return new TodoResource(true, 'Data berhasil ditambahkan', $todo);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data!',
                'error' => $th->getMessage()
            ]);
        }
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
        // Jika hanya update status
        if ($request->has('status') && !$request->has('title') && !$request->has('description')) {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $todo->update(['status' => $request->status]);

            return new TodoResource(true, 'Status berhasil diubah', $todo->fresh());
        }

        // Validasi lengkap
        $validator = Validator::make($request->all(), [
            'image'       => 'nullable',
            'image.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'       => 'required',
            'description' => 'required',
            'status'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }



        $images = Image::where('todo_id', $todo->id);
        // return response()->json([
        //     'debug' => $images
        // ]);

        // Jika ada gambar baru, hapus lama lalu simpan baru
        if ($request->hasFile('image')) {
            if($images) {
                foreach ($images->get() as $image) {
                    $oldImg = $image->image;
                    if (File::exists(public_path('todo/' . $oldImg))) {
                        File::delete(public_path('todo/' . $oldImg));
                    }
                }
            }

            $images->delete();

            foreach ($request->file('image') as $img) {
                $imgName = time() . '_' . $img->hashName();
                $img->move(public_path('todo'), $imgName);
                Image::create([
                    'todo_id' => $todo->id,
                    'image' => $imgName
                ]);
            }
        }

        $todo->update([
            'user_id' => auth('api')->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return new TodoResource(true, 'Data berhasil diubah', $todo->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Todo $todo)
    {
        $images = Image::where('todo_id', $todo->id)->get();
        if($images) {
            foreach ($images as $img) {
                $oldImage = $img->image;
                if (File::exists(public_path('todo/' . $oldImage))) {
                    File::delete(public_path('todo/' . $oldImage));
                }
            }
        }

        $todo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data telah dihapus',
            'data'    => null,
        ], 200);
    }
}
