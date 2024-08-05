<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isOwner'])->only('store', 'destroy');
    }

    public function dashboard()
    {
        $limitBooks = Book::with('category')->orderBy('created_at', 'desc')->take(10)->get();
        return response ()->json([
            "message" => "View Limited Books",
            "data" => $limitBooks
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->get();

        return response()->json([
            "message" => "View all books",
            "data" => $books
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        Book::create($data);

        return response()->json([
            "message" => "Book successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with('category', 'list_barrows')->find($id);

        if(!$book){
            return response()->json([
                "message" => "Book with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $book
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {
        $data = $request->validated();

        $book = Book::find($id);
        if(!$book){
            return response()->json([
                "message" => "Book with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($book->image) {
                $publicId = pathinfo($book->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $book->update($data);

        return response()->json([
            "message" => "Book with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if(!$book){
            return response()->json([
                "message" => "Book with ID $id is not found"
            ], 404);
        }

        if($book->image){
            $imageName = basename($book->image);
            Storage::delete('public/image/' . $imageName);
        }

        $book->delete();

        return response()->json([
            "message" => "Book successfully deleted"
        ], 200);
    }
}
