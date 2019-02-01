<?php

namespace App;

use App\Collections\ModuleCollection;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    /**
     * Module has infusion id
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function infusionId()
    {
        return $this->hasOne(ModuleTagId::class, 'module_id', 'id');
    }

    /**
     * Return ModuleCollection instace
     *
     * @param array $models
     * @return ModuleCollection|\Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new ModuleCollection($models);
    }
}
