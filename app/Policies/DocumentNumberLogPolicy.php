<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DocumentNumberLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentNumberLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DocumentNumberLog');
    }

    public function view(AuthUser $authUser, DocumentNumberLog $documentNumberLog): bool
    {
        return $authUser->can('View:DocumentNumberLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DocumentNumberLog');
    }

    public function update(AuthUser $authUser, DocumentNumberLog $documentNumberLog): bool
    {
        return $authUser->can('Update:DocumentNumberLog');
    }

    public function delete(AuthUser $authUser, DocumentNumberLog $documentNumberLog): bool
    {
        return $authUser->can('Delete:DocumentNumberLog');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DocumentNumberLog');
    }

    public function restore(AuthUser $authUser, DocumentNumberLog $documentNumberLog): bool
    {
        return $authUser->can('Restore:DocumentNumberLog');
    }

    public function forceDelete(AuthUser $authUser, DocumentNumberLog $documentNumberLog): bool
    {
        return $authUser->can('ForceDelete:DocumentNumberLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DocumentNumberLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DocumentNumberLog');
    }

    public function replicate(AuthUser $authUser, DocumentNumberLog $documentNumberLog): bool
    {
        return $authUser->can('Replicate:DocumentNumberLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DocumentNumberLog');
    }

}