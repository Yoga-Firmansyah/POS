<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->orderBy('name')->get();
        return response()->json([
            'success'       => true,
            'message'       => 'List Data Users',
            'users'    => $users,
        ], 200);
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
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'password'   => 'required|confirmed',
            'role'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $user = User::create([
                    'name'   => $request->name,
                    'email'  => $request->email,
                    'password'  => bcrypt($request->password),
                ]);
                $user->assignRole($request->role);
                $user->roles;
                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved Successfully!',
                    'data' => $user,
                ], 200);
            } catch (QueryException $exception) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Failed to Save!',
                    'error' => $exception->errorInfo[2]
                ], 400);
            }
        }
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
    public function edit(User $user)
    {
        $user->hasExactRoles(Role::all());
        return response()->json([
            'success' => true,
            'message' => 'Data Edit User',
            'data'    => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|confirmed',
            'role'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $user->hasExactRoles(Role::all());
                if ($request->password == "") {
                    $user->update([
                        'name'   => $request->name,
                        'email'  => $request->email,
                    ]);
                } else {
                    $user->update([
                        'name'   => $request->name,
                        'email'  => $request->email,
                        'password'  => bcrypt($request->password),
                    ]);
                }
                $roles = $user->roles;
                foreach ($roles as $role) {
                    $user->removeRole($role);
                }
                $user->assignRole($request->role);
                $user->roles;
                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved Successfully!',
                    'data' => $user,
                ], 200);
            } catch (QueryException $exception) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Failed to Save!',
                    'error' => $exception->errorInfo[2]
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (QueryException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->errorInfo[2]
            ], 500);
        }
    }

    public function getRoles()
    {
        $allRolesInDatabase = Role::all()->pluck('name');
        return response()->json([
            'success'       => true,
            'message'       => 'List Data Users',
            'roles'    => $allRolesInDatabase,
        ], 200);
    }
}
