<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            $roles = Role::all();
            return response()->json($roles);
        }
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
            if($request->wantsJson()){
            $role = Role::find($id);
            $role->delete();
            return response()->json($role);
        }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
