<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\TableTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Validator;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $permissions = Permission::query()
                ->when($request->filled('name'), function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->input('name') . '%');
                });

            $permissions = TableTrait::sortAndPaginate($request, $permissions);

            return response()->json($permissions);
        }

        return view('admin.permissions');
    }

    public function store(Request $request)
    {
        try {
            $permission = Permission::create([
                'name' => $request->name,
            ]);
            return response()->json(['message' => 'Permiso creado correctamente.','status' => 200, 'data' => $permission], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->update([
                'name' => $request->name,
            ]);
            return response()->json([
                'message' => 'Permiso actualizado correctamente.'],200);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json([
            'message' => 'Permiso eliminado correctamente.'
        ],200);
    }

}
