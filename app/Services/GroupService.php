<?php

namespace App\Services;

use App\Models\Group;

class GroupService
{
    public function createGroup($name)
    {
        return Group::create(['name' => $name]);
    }

    public function updateGroup($id, $name)
    {
        $group = Group::findOrFail($id);
        $group->name = $name;
        $group->save();
        return $group;
    }
}
