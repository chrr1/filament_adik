<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->user_group_id === 1; // Hanya admin yang bisa melihat semua user
    }

    public function view(User $user, User $model)
    {
        return $user->user_group_id === 1 || $user->id === $model->id; // Admin bisa melihat semua, user biasa hanya bisa melihat diri sendiri
    }

    // Tambahkan metode lainnya jika diperlukan
}
