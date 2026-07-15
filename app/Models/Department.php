<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'categories',
    ];

    protected function casts(): array
    {
        return [
            'categories' => 'json',
        ];
    }
}
