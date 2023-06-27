<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Services\Admin\RoleService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Silber\Bouncer\Database\Role;

class RoleController extends Controller
{
    public function __construct(protected RoleService $service) {}

    public function index(Request $request)
    {
        $role = $this->service->filter($request);

        return Inertia::render('Admin/Roles', [
            'roles' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Roles/Create');
    }

    public function store(RoleRequest $request)
    {
        $res = $this->service->store($request);

        return response()->jsonApi($res);
    }

    public function show(Request $request, Role $role)
    {
        $role = $this->service->append($role, [
            'created_at_formatted',
            'updated_at_formatted'
        ]);

        return Inertia::render('Admin/Roles/Show', [
            'role' => $role,
        ]);
    }

    public function edit(Request $request, Role $role)
    {
        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role,
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $res = $this->service->update($request, $role);

        return response()->jsonApi($res);
    }
    
    public function destroy(Role $role)
    {
        $res = $this->service->destroy($role->id);

        return response()->jsonApi($res);
    }

}
