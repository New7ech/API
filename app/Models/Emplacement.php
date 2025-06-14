<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emplacement extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'emplacements';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
}
