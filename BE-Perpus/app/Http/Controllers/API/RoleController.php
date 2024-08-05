<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::all();

        return response()->json([
            "message" => "View all Roles",
            "data" => $role
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        Role::create($request->all());

        return response()->json([
            "message" => "Role successfully added"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);

        if(!$role){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Data with ID : $id",
            "data" => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        $role = Role::find($id);

        if(!$role){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $role->update($validatedData);

        $role->name = $request['name'];
        $role->save();

        return response()->json([
            "message" => "Role with ID $id successfully updated",
            "data" => $role
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if(!$role){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $role->delete();
        return response()->json([
            "message" => "Role with ID $id successfully deleted"
        ]);
    }
}
