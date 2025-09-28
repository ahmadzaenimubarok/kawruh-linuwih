<?php

namespace App\Filament\Resources\ProjectStages\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\ProjectStages\ProjectStageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectStage extends EditRecord
{
    protected static string $resource = ProjectStageResource::class;

    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        
        // Add Projects breadcrumb
        $breadcrumbs[url(ProjectResource::getUrl())] = 'Projects';
        
        // Add Project Stages breadcrumb with project_id parameter from the current record
        $projectId = $this->record->project_id;
        $breadcrumbs[url(static::getResource()::getUrl('index', ['project_id' => $projectId]))] = 'Project Stages';
        
        // Add current page breadcrumb
        $breadcrumbs[null] = 'Edit';
        
        return $breadcrumbs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Redirect back to project stages list with project_id filter
        $projectId = $this->record->project_id;
        return $this->getResource()::getUrl('index', ['project_id' => $projectId]);
    }
}
