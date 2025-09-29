<?php

namespace App\Filament\Resources\ProjectStages\Schemas;

use App\Models\Project;
use App\Models\ProjectStage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectStageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('project_id')
                    ->label('Project')
                    ->options(Project::all()->pluck('title', 'id'))
                    ->required()
                    ->searchable()
                    ->disabled(fn (?ProjectStage $record) => ($record && $record->exists) || request()->has('project_id'))
                    ->default(fn () => request()->get('project_id'))
                    ->dehydrated(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('instructions')
                    ->required()
                    ->rows(4),
                TextInput::make('order_no')
                    ->label('Order Number')
                    ->numeric()
                    ->required()
                    ->default(function () {
                        $projectId = request()->get('project_id');
                        
                        if ($projectId) {
                            // Get the highest order number for this project
                            $maxOrder = ProjectStage::where('project_id', $projectId)
                                ->max('order_no');
                            
                            // Return next order number (max + 1, or 1 if no stages exist)
                            return $maxOrder ? $maxOrder + 1 : 1;
                        }
                        
                        // Default to 1 if no project_id is available
                        return 1;
                    }),
            ]);
    }
}
