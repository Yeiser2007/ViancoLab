<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Traits\TableTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

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
                ->when($request->filled('name'), function ($query) use ($request) {
                    return $query->ofName($request->input('name'));
                })
                ->when($request->filled('role'), function ($query) use ($request) {
                    return $query->ofRole($request->input('role'));
                });
            $users = TableTrait::sortAndPaginate($request, $users);

            return response()->json($users);
        }

        return view('admin.users');
    }

    public function store(StoreUsersRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create($request->validated());
            $user->syncRoles($request->roles);
        });

        return response()->json([
            'message' => 'Usuario creado correctamente'
        ], 200);
    }

    public function update(UpdateUsersRequest $request, User $user)
    {
        try {
            $data = $request->except(['role', 'password']);
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password); // Hashear la nueva contraseña
            }
            $user->update($data);
            if ($request->has('role')) {
                $role = Role::findById($request->role, 'web');
                $user->syncRoles([$role->name]);
            }
            return response()->json([
                'message' => 'Usuario actualizado correctamente',
                'user' => $user->refresh()->load('roles')
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error al actualizar usuario',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function show(User $users)
    {
        if ($users->exists) {
            $users = $users->load('roles');
            return response()->json($users);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

    }
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error al eliminar usuario',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
