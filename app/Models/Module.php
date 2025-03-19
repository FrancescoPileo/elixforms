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
        //TODO: correggere

        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')
            ->join('groups', 'groups.id', '=', 'group_user.group_id')
            ->join('modules', 'modules.group_id', '=', 'groups.id')
            ->whereColumn('groups.id', 'modules.group_id')
            ->select('users.*');
            
        /*
        return $this->hasManyThrough(
            User::class, 
            Group::class, // Tabella intermedia (groups)
            'id', // Chiave primaria della tabella groups
            'id', // Chiave primaria della tabella users
            'group_id', // Chiave esterna in modules che collega ai groups
            'id' // La chiave esterna in group_user che collega a users (Laravel lo risolve automaticamente)
            );
        */
    }
}
