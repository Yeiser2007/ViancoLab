<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Traits\TableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $users = User::with('roles')
                ->when($request->input('name'), function ($query, $search) {
                    return $query->ofName($search);
                })
                ->when($request->input('role'), function ($query, $role) {
                    return $query->ofRole($role);
                })
                ->when($request->has('status'), function ($query) use ($request) {
                    return $query->where('active', $request->boolean('status'));
                });

            $users = TableTrait::sortAndPaginate($request, $users);

            return response()->json($users);
        }

        return view('admin.users');
    }
    public function store(StoreUsersRequest $request)
    {
        try {
            if ($request->wantsJson()) {
                $user = User::create($request->all());
                $user->assignRole($request->role);
                return response()->json(['message' => 'Usuario agregado correctamente'], 200);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function show(User $users)
    {
        if ($users->exists) {
            return response()->json($users);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

    }
    public function update(UpdateUsersRequest $request, User $users)
    {
        try {
            if ($request->wantsJson()) {
                $users->update($request->all());
                $users->syncRoles($request->role);
                return response()->json(['message' => 'Usuario actualizado correctamente'], 200);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function destroy(User $users)
    {
        try {
            $users->delete();
            return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
        } catch (\Throwable $e) {
            # code...
        }
    }
}
