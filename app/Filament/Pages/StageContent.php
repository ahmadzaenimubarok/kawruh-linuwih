<?php

namespace App\Filament\Pages;

use App\Models\Project;
use App\Models\ProjectStage;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use App\Services\AIChatService;

class StageContent extends Page
{
    protected string $view = 'filament.pages.stage-content';

    protected static ?string $title = 'Stage Content';

    protected static bool $shouldRegisterNavigation = false;

    public ?ProjectStage $stage = null;
    public ?Project $project = null;
    public ?string $projectSlug = null;
    public ?int $orderNo = null;
    public ?array $questions = [];

    public function mount(?string $projectSlug = null, ?int $orderNo = null): void
    {
        $this->projectSlug = $projectSlug;
        $this->orderNo = $orderNo ?? 1; // Default to first stage if no order specified
        
        if ($projectSlug) {
            // Find project by slug
            $this->project = Project::where('slug', $projectSlug)->first();
            
            if ($this->project) {
                // Find stage by project and order number
                $this->stage = ProjectStage::where('project_id', $this->project->id)
                    ->where('order_no', $this->orderNo)
                    ->with('project')
                    ->first();
                    
                // If stage not found, get the first stage of the project
                if (!$this->stage) {
                    $this->stage = ProjectStage::where('project_id', $this->project->id)
                        ->orderBy('order_no')
                        ->with('project')
                        ->first();
                    $this->orderNo = $this->stage?->order_no ?? 1;
                }
            }
        }
        
        // Fallback: get first stage if no parameters provided
        if (!$this->stage) {
            $this->stage = ProjectStage::with('project')->first();
            if ($this->stage) {
                $this->project = $this->stage->project;
                $this->projectSlug = $this->project->slug;
                $this->orderNo = $this->stage->order_no;
            }
        }
    }

    public function generateQuestion()
    {
        $aiChatService = app(AIChatService::class);
        $this->questions = $aiChatService->generateQuestion($this->stage->instructions);
    }

    public function getTitle(): string|Htmlable
    {
        return $this->stage ? $this->stage->title : 'Stage Content';
    }

    protected function getViewData(): array
    {
        return [
            'stage' => $this->stage,
            'project' => $this->project,
            'projectSlug' => $this->projectSlug,
            'orderNo' => $this->orderNo,
            'questions' => $this->questions,
        ];
    }

    public function hasPreviousStage(): bool
    {
        if (!$this->stage) {
            return false;
        }

        return ProjectStage::where('project_id', $this->stage->project_id)
            ->where('order_no', '<', $this->stage->order_no)
            ->exists();
    }

    public function hasNextStage(): bool
    {
        if (!$this->stage) {
            return false;
        }

        return ProjectStage::where('project_id', $this->stage->project_id)
            ->where('order_no', '>', $this->stage->order_no)
            ->exists();
    }

    public function previousStage(): void
    {
        if (!$this->hasPreviousStage()) {
            return;
        }

        $previousStage = ProjectStage::where('project_id', $this->stage->project_id)
            ->where('order_no', '<', $this->stage->order_no)
            ->orderBy('order_no', 'desc')
            ->first();

        if ($previousStage) {
            $this->orderNo = $previousStage->order_no;
            redirect()->route('filament.learn.pages.stage-content', [
                'projectSlug' => $this->projectSlug,
                'orderNo' => $this->orderNo
            ]);
        }
    }

    public function nextStage(): void
    {
        if (!$this->hasNextStage()) {
            return;
        }

        $nextStage = ProjectStage::where('project_id', $this->stage->project_id)
            ->where('order_no', '>', $this->stage->order_no)
            ->orderBy('order_no', 'asc')
            ->first();

        if ($nextStage) {
            $this->orderNo = $nextStage->order_no;
            redirect()->route('filament.learn.pages.stage-content', [
                'projectSlug' => $this->projectSlug,
                'orderNo' => $this->orderNo
            ]);
        }
    }

}
