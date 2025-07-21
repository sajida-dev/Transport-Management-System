<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value'
    ];

    // Convert value to appropriate type when accessing
    public function getValueAttribute($value)
    {
        // Handle boolean values stored as strings
        if ($value === 'true') return true;
        if ($value === 'false') return false;

        // Handle JSON values
        if ($this->isJson($value)) {
            return json_decode($value, true);
        }

        return $value;
    }

    // Convert value to string when storing
    public function setValueAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = (string) $value;
        }
    }

    private function isJson($string)
    {
        if (!is_string($string)) return false;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}