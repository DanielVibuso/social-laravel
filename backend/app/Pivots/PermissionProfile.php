<?php

namespace App\Pivots;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionProfile extends Pivot
{
    use UsesUuid;

    protected $table = 'permission_profile';
}
