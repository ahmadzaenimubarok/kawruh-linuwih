<?php

namespace App\Filament\Resources\ProjectStages\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\ProjectStages\ProjectStageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProjectStage extends CreateRecord
{
    protected static string $resource = ProjectStageResource::class;

    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        
        // Add Projects breadcrumb
        $breadcrumbs[url(ProjectResource::getUrl())] = 'Projects';
        
        // Add Project Stages breadcrumb with project_id parameter if available
        if (request()->has('project_id')) {
            $breadcrumbs[url(static::getResource()::getUrl('index', ['project_id' => request()->get('project_id')]))] = 'Project Stages';
        } else {
            $breadcrumbs[url(static::getResource()::getUrl('index'))] = 'Project Stages';
        }
        
        // Add current page breadcrumb
        $breadcrumbs[null] = 'Create';
        
        return $breadcrumbs;
    }

    /**
     * Get the URL to redirect to after the record is created.
     */
    protected function getRedirectUrl(): string
    {
        if (request()->has('project_id')) {
            return $this->getResource()::getUrl('index', ['project_id' => request()->get('project_id')]);
        }
        return $this->getResource()::getUrl('index');
    }
}
