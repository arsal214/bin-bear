<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    protected $fillable  = ['name','parent_id','is_popular','status','price'];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function getImageAttribute($value)
    {
        return $value ? '/images/categories/'.$value : null;
    }


    

    // Define parent category relation
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Define children categories relation
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name', 'asc')->with('children');
    }
}
