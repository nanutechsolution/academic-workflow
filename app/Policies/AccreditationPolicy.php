<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Accreditation;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccreditationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Accreditation');
    }

    public function view(AuthUser $authUser, Accreditation $accreditation): bool
    {
        return $authUser->can('View:Accreditation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Accreditation');
    }

    public function update(AuthUser $authUser, Accreditation $accreditation): bool
    {
        return $authUser->can('Update:Accreditation');
    }

    public function delete(AuthUser $authUser, Accreditation $accreditation): bool
    {
        return $authUser->can('Delete:Accreditation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Accreditation');
    }

    public function restore(AuthUser $authUser, Accreditation $accreditation): bool
    {
        return $authUser->can('Restore:Accreditation');
    }

    public function forceDelete(AuthUser $authUser, Accreditation $accreditation): bool
    {
        return $authUser->can('ForceDelete:Accreditation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Accreditation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Accreditation');
    }

    public function replicate(AuthUser $authUser, Accreditation $accreditation): bool
    {
        return $authUser->can('Replicate:Accreditation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Accreditation');
    }

}