<?php

namespace App\Policies;

use App\Models\Pengaduan;
use App\Models\User;

class PengaduanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pengaduan');
    }

    public function view(User $user, Pengaduan $pengaduan): bool
    {
        return $user->can('view_pengaduan');
    }

    public function create(User $user): bool
    {
        return $user->can('create_pengaduan');
    }

    public function update(User $user, Pengaduan $pengaduan): bool
    {
        return $user->can('update_pengaduan');
    }

    public function delete(User $user, Pengaduan $pengaduan): bool
    {
        return $user->can('delete_pengaduan');
    }

    public function restore(User $user, Pengaduan $pengaduan): bool
    {
        return $user->can('restore_pengaduan');
    }

    public function forceDelete(User $user, Pengaduan $pengaduan): bool
    {
        return $user->can('force_delete_pengaduan');
    }
}
