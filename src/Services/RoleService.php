<?php

namespace App\Services\Admin;

use App\Models\Admin\Ability;
use App\Models\Admin\Role;
use Bouncer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public function filter($params)
    {
        $q = Role::with(['abilities']);

        $q->when($params->name ?? false, function ($query) use ($params) {
            $query->where('name', $params->name);
            $query->orWhere('title', $params->name);
        });

        return $q->paginate();
    }

    public function store($req)
    {
        DB::beginTransaction();
        try {
            $role = Bouncer::role()->firstOrCreate([
                'title' => $req->title,
                'name' => $req->name,
            ]);
            // $req->abilities = ids of marked abilities
            Bouncer::sync($role->name)->abilities($req->abilities);

            DB::commit();
            // attach abilities to response
            $role->load('abilities');

            return $role;

        } catch (\Throwable $th) {
            DB::rollBack();
            info($th->getMessage());
        }

        return false;
    }

    public function update($req, $entity)
    {
        DB::beginTransaction();
        try {
            $entity->name = $req->name;
            $entity->title = $req->title;
            $entity->save();
            // $req->abilities = ids of marked abilities
            Bouncer::sync($entity->name)->abilities($req->abilities);

            DB::commit();

            $entity->load('abilities');

            return $entity;

        } catch (\Throwable $th) {
            DB::rollBack();
            info($th->getMessage());
        }

        return false;
    }

    public function destroy($id)
    {
        try {
            Role::where('id', $id)
                ->delete();

            return true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return false;
    }

    /**
     * Append formatted properties to object
     *
     * @param  object  $role
     * @param  array  $fields property to append
     * @return object
     */
    public function append($role, $fields = [])
    {
        if (in_array('created_at_formatted', $fields)) {
            $role->created_at_formatted = Carbon::parse($role->created_at)->format('M d, Y G:i A');
        }

        if (in_array('updated_at_formatted', $fields)) {
            $role->updated_at_formatted = Carbon::parse($role->created_at)->format('M d, Y G:i A');
        }

        return $role;
    }

    /**
     * Check if Create Button should be enabled
     * Serve as a toggle for
     *  "There are no existing abilities. Please create at least one."
     *  indicator
     *
     * @return object
     */
    public function hasExistingAbility()
    {
        return Ability::select('id')->first();
    }

    /**
     * Toggle all abilities that matches each abilities of a role
     * appends "marked" property into the object (collection)
     *
     * @param Illuminate/Database/Eloquent/Collection   $roleAbilities  Abiltiies of a role
     * @param Illuminate/Database/Eloquent/Collection   $abilities      All abilities
     * @return Illuminate/Database/Eloquent/Collection|Array    Marked Abilities grouped by key
     */
    public function markAbilities($entityAbilities, $returnArray = true)
    {
        $abilities = Ability::all();

        $abilities->each(function (&$ability, $key) use ($entityAbilities) {
            $ability->marked = $entityAbilities->contains('id', $ability->id);
        });

        return $returnArray ? groupByKey($abilities) : $returnArray;
    }
}
