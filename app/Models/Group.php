<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_elix_id',
        'group_des',
    ];

    /**
     * Relationship with Module
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Relationship with User
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
