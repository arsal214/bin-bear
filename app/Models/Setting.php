<?php

namespace App\Models;

use App\Traits\LogsChangesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{
    use HasFactory;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'value'];

    static function get($name)
    {
        return self::where('name', $name)->value('value');
    }

    static function set($data)
    {
        foreach ($data as $value) {
            $attributes = [
                'value' => $value['value'],
            ];

            $searchAttributes = [
                'name' => $value['name'],
            ];

            Setting::updateOrCreate($searchAttributes, $attributes);
        }
    }
}
