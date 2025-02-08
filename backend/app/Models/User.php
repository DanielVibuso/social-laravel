<?php

namespace App\Models;

use App\Casts\Hashed;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'name',
        'password',
        'profile_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => Hashed::class,
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function getPermissions()
    {
        $permissions = [];

        foreach ($this->profile->permissions as $permission) {
            $permissions[] = $permission->name;
        }

        return $permissions;
    }

    public function socialAccounts() 
    { 
        return $this->hasMany(SocialAccount::class); 
    }
}
