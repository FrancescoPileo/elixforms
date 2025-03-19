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
        return $this->hasManyThrough(
            User::class, // Tabella di destinazione (users)
            Group::class, // Tabella intermedia (groups)
            'id', // Chiave primaria della tabella groups
            'id', // Chiave primaria della tabella users
            'group_id', // Chiave esterna in modules che collega ai groups
            'id' // La chiave esterna in group_user che collega a users (Laravel lo risolve automaticamente)
        );
    }
}
