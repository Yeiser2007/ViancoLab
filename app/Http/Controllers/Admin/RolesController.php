<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Traits\TableTrait;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $roles = Role::query()
                ->when($request->filled('name'), function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->input('name') . '%');
                });

            $roles = TableTrait::sortAndPaginate($request, $roles);

            return response()->json($roles);
        }

        return view('admin.roles');
    }
    public function store(Request $request)
    {
        try {
            if ($request->wantsJson()) {
                $role = Role::create($request->all());
                return response()->json($role);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    public function update(Request $request, $id)
    {
        try {
            if ($request->wantsJson()) {
                $role = Role::find($id);
                $role->update($request->all());
                return response()->json($role);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function destroy(Request $request, $id)
    {
        try {
            if ($request->wantsJson()) {
                $role = Role::find($id);
                $role->delete();
                return response()->json($role);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAllPermissions()
    {
        try {
            return response()->json([
                'data' => Permission::orderBy('name')->get(['id', 'name'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los permisos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPermissionsByRole($roleId)
    {
        try {
            $role = Role::with('permissions:id')->findOrFail($roleId);
            $rolePermissionIds = $role->permissions->pluck('id')->toArray();

            $permissions = Permission::orderBy('name')
                ->get(['id', 'name'])
                ->map(function ($permission) use ($rolePermissionIds) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'has_permission' => in_array($permission->id, $rolePermissionIds)
                    ];
                });

            return response()->json(['data' => $permissions]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los permisos del rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Obtener permisos asignados a un rol
     */
    public function updateRolePermissions(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id'
            ]);
            $role = Role::findOrFail($id);
            $role->syncPermissions($validated['permissions']);
            $role->load('permissions');
            return response()->json([
                'success' => true,
                'message' => 'Permisos actualizados correctamente',
                'data' => [
                    'role' => $role,
                    'permissions' => $role->permissions
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no encontrado',
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar permisos',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
