<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role as SpetieRole;

class Role extends SpetieRole
{
    use HasFactory;

    public $guard_name = ['web','api'];

    protected $fillable = [
        'guard_name', 'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
