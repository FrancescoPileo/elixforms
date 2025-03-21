<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_tag',
        'module_des',
        'group_id',
    ];

    /**
     * Relationship with Group
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Relationship with Group -> User
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'group_user',     // Nome della tabella pivot
            'group_id',       // Foreign key della tabella corrente (Module.group_id)
            'user_id',        // Foreign key della tabella relazionata (User.id)
            'group_id',       // Local key su Module
            'id'              // Local key su Group (che combacia con group_user.group_id)
        );
    }
}
