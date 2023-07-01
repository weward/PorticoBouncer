<?php

namespace App\Services\Admin;

use App\Models\Admin\Ability;
use Carbon\Carbon;

class AbilityService
{
    public function filter($params)
    {
        $q = Ability::query();

        $q->when($params->name ?? false, function ($query) use ($params) {
            $query->where('name', $params->name);
            $query->orWhere('title', $params->name);
        });

        return $q->paginate();
    }

    public function store($req)
    {
        try {
            $q = Ability::create([
                'name' => $req->name,
                'title' => $req->title,
            ]);

            info($q);

            return $q;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return false;
    }

    public function update($req, $entity)
    {
        try {
            $entity->name = $req->name;
            $entity->title = $req->title;
            $entity->save();

            return $entity;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return false;
    }

    public function destroy($id)
    {
        try {
            Ability::where('id', $id)->delete();

            return true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return false;
    }

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
     * Returns grouped abilities based on prefix (Ability's title)
     * ie,
     *  users => [users.create, users.edit],
     *  reports => [reports.create, reports.download]
     */
    public function getAll($grouped = false)
    {
        $abilities = Ability::all();

        return $grouped ? groupByKey($abilities) : $abilities;
    }
}
