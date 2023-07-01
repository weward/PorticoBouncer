<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Admin\Role;
use App\Services\Admin\AbilityService;
use App\Services\Admin\RoleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function __construct(protected RoleService $service, protected AbilityService $abilityService)
    {
    }

    public function index(Request $request)
    {
        $role = $this->service->filter($request);
        $hasExistingAbility = $this->service->hasExistingAbility();

        return Inertia::render('Admin/Roles', [
            'roles' => $role,
            'hasExistingAbility' => $hasExistingAbility,
        ]);
    }

    public function create()
    {
        $abilities = $this->abilityService->getAll();

        return Inertia::render('Admin/Roles/Create', [
            'abilities' => $abilities,
        ]);
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
            'updated_at_formatted',
        ]);

        $role->load(['abilities']);

        return Inertia::render('Admin/Roles/Show', [
            'role' => $role,
        ]);
    }

    public function edit(Request $request, Role $role)
    {
        $role->load('abilities');
        // Toggle all abilities that matches each abilities of this role
        $markedAbilities = $this->service->markAbilities($role->abilities);

        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role,
            'abilities' => $markedAbilities,
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
