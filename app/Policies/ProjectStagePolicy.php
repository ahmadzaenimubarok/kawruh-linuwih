<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProjectStage;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectStagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProjectStage');
    }

    public function view(AuthUser $authUser, ProjectStage $projectStage): bool
    {
        return $authUser->can('View:ProjectStage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProjectStage');
    }

    public function update(AuthUser $authUser, ProjectStage $projectStage): bool
    {
        return $authUser->can('Update:ProjectStage');
    }

    public function delete(AuthUser $authUser, ProjectStage $projectStage): bool
    {
        return $authUser->can('Delete:ProjectStage');
    }

    public function restore(AuthUser $authUser, ProjectStage $projectStage): bool
    {
        return $authUser->can('Restore:ProjectStage');
    }

    public function forceDelete(AuthUser $authUser, ProjectStage $projectStage): bool
    {
        return $authUser->can('ForceDelete:ProjectStage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProjectStage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProjectStage');
    }

    public function replicate(AuthUser $authUser, ProjectStage $projectStage): bool
    {
        return $authUser->can('Replicate:ProjectStage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProjectStage');
    }

}