<?php

namespace App\Filament\Pages;

use App\Models\Project;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;

class DashboardStudent extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static ?string $title = 'Dashboard Student';
    protected static ?string $navigationLabel = 'Dashboard Student';
    protected string $view = 'filament.pages.dashboard-student';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Project::query()->with(['creator', 'stages']))
            ->columns([
                TextColumn::make('title')
                    ->label('Project Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(100)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 100) {
                            return null;
                        }
                        return $state;
                    }),
                
                BadgeColumn::make('difficulty_level')
                    ->label('Difficulty')
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate', 
                        'danger' => 'advanced',
                    ])
                    ->icons([
                        'heroicon-o-star' => 'beginner',
                        'heroicon-o-fire' => 'intermediate',
                        'heroicon-o-bolt' => 'advanced',
                    ]),
                
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable(),
                
                TextColumn::make('stages_count')
                    ->label('Stages')
                    ->counts('stages')
                    ->badge()
                    ->color('primary'),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('difficulty_level')
                    ->label('Difficulty Level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ])
                    ->placeholder('All Levels'),
            ])
            ->actions([
                Action::make('view_stages')
                    ->label('View Stages')
                    ->icon('heroicon-o-list-bullet')
                    ->color('info')
                    ->url(fn (Project $record): string => 
                        route('filament.learn.resources.project-stages.index', [
                            'tableFilters' => [
                                'project_id' => ['value' => $record->id]
                            ]
                        ])
                    ),
                
                Action::make('start_learning')
                    ->label('Start Learning')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->action(function (Project $record) {
                        // Placeholder for start learning functionality
                        Notification::make()
                            ->title('Project Started')
                            ->body("Starting project: {$record->title}")
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
