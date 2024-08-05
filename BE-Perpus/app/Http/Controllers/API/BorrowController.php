<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;

class BorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isOwner'])->only('index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'load_date' => 'required|date_format:Y-m-d H:i:s',
            'barrow_date' => 'required|date_format:Y-m-d H:i:s',
            'book_id' => 'required|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currentUser = auth()->user();

        $borrow = Borrow::updateOrCreate(
            [   'user_id'=> $currentUser->id,
                'book_id' => $request['book_id']
            ],
            [
                'load_date' => $request['load_date'],
                'barrow_date' => $request['barrow_date'],
                'book_id' => $request['book_id']
            ]
        );

        return response()->json([
            "message" => "Successfully add/update borrow",
            "data" => $borrow
        ], 201);
    }

    public function index()
    {
        $borrow = Borrow::with(['user', 'books'])->orderBy('barrow_date', 'asc')->get();

        return response()->json([
            "message" => "View all movie's casts",
            "data" => $borrow
        ]);
    }
}
