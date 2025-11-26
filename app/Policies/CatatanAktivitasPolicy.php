<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CatatanAktivitas;
use Illuminate\Auth\Access\HandlesAuthorization;

class CatatanAktivitasPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CatatanAktivitas');
    }

    public function view(AuthUser $authUser, CatatanAktivitas $catatanAktivitas): bool
    {
        return $authUser->can('View:CatatanAktivitas');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CatatanAktivitas');
    }

    public function update(AuthUser $authUser, CatatanAktivitas $catatanAktivitas): bool
    {
        return $authUser->can('Update:CatatanAktivitas');
    }

    public function delete(AuthUser $authUser, CatatanAktivitas $catatanAktivitas): bool
    {
        return $authUser->can('Delete:CatatanAktivitas');
    }

    public function restore(AuthUser $authUser, CatatanAktivitas $catatanAktivitas): bool
    {
        return $authUser->can('Restore:CatatanAktivitas');
    }

    public function forceDelete(AuthUser $authUser, CatatanAktivitas $catatanAktivitas): bool
    {
        return $authUser->can('ForceDelete:CatatanAktivitas');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CatatanAktivitas');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CatatanAktivitas');
    }

    public function replicate(AuthUser $authUser, CatatanAktivitas $catatanAktivitas): bool
    {
        return $authUser->can('Replicate:CatatanAktivitas');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CatatanAktivitas');
    }

}