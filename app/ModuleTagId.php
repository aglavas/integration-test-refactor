<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleTagId extends Model
{
    protected $fillable = ['module_id', 'infusion_id', 'completed'];

    public $timestamps = false;
}
