<?php

namespace App\Models;

use App\Enums\ProfileEnum;
use App\Pivots\PermissionProfile;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_profile')
                    ->using(PermissionProfile::class)
                    ->withTimestamps();
    }

    public function scopeUserRightAccess(Builder $query): void
    {
        $query->when(auth()->user()->profile->name != ProfileEnum::ADMIN->value, function ($query) {
            $query->where('name', auth()->user()->profile->name);
        });
    }
}
