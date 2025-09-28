<?php

namespace App\Filament\Resources\ProjectStages\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\ProjectStages\ProjectStageResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProjectStages extends ListRecords
{
    protected static string $resource = ProjectStageResource::class;

    public function getTitle(): string
    {
        if (request()->has('project_id')) {
            $project = Project::find(request()->get('project_id'));
            if ($project) {
                return "Project Stages - {$project->title}";
            }
        }
        
        return 'Project Stages';
    }

    public function getBreadcrumbs(): array
    {
        return [
            // breadcrumb manual ke ProjectResource
            url(ProjectResource::getUrl()) => 'Projects',

            // breadcrumb otomatis resource sekarang
            url(static::getResource()::getUrl()) => 'Project Stages',

            // breadcrumb halaman ini
            null => 'List',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(function () {
                    $url = static::getResource()::getUrl('create');
                    
                    // Add project_id parameter if it exists in current request
                    if (request()->has('project_id')) {
                        $url .= '?project_id=' . request()->get('project_id');
                    }
                    
                    return $url;
                }),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        
        // Check if project_id parameter exists in URL
        if (request()->has('project_id')) {
            $projectId = request()->get('project_id');
            $query->where('project_id', $projectId);
        }
        
        return $query;
    }

    public function getDefaultTableFiltersState(): array
    {
        $filters = [];
        
        // Set default filter state if project_id is in URL
        if (request()->has('project_id')) {
            $filters['project_id'] = request()->get('project_id');
        }
        
        return $filters;
    }
}
