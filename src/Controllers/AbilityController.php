<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AbilityRequest;
use App\Models\Admin\Ability;
use App\Services\Admin\AbilityService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AbilityController extends Controller
{
    public function __construct(protected AbilityService $service)
    {
    }

    public function index(Request $request)
    {
        $abilities = $this->service->filter($request);

        return Inertia::render('Admin/Abilities/Index', [
            'abilities' => $abilities,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Abilities/Create');
    }

    public function store(AbilityRequest $request)
    {
        $res = $this->service->store($request);

        return response()->jsonApi($res);
    }

    public function show(Request $request, Ability $ability)
    {
        $ability = $this->service->append($ability, [
            'created_at_formatted',
            'updated_at_formatted',
        ]);

        return Inertia::render('Admin/Abilities/Show', [
            'ability' => $ability,
        ]);
    }

    public function edit(Request $request, Ability $ability)
    {
        return Inertia::render('Admin/Abilities/Edit', [
            'ability' => $ability,
        ]);
    }

    public function update(AbilityRequest $request, Ability $ability)
    {
        $res = $this->service->update($request, $ability);

        return response()->jsonApi($res);
    }

    public function destroy(Ability $ability)
    {
        $res = $this->service->destroy($ability->id);

        return response()->jsonApi($res);
    }
}
