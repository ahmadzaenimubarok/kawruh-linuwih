<?php

namespace App\Filament\Resources\ProjectStages;

use App\Filament\Resources\ProjectStages\Pages\CreateProjectStage;
use App\Filament\Resources\ProjectStages\Pages\EditProjectStage;
use App\Filament\Resources\ProjectStages\Pages\ListProjectStages;
use App\Filament\Resources\ProjectStages\Schemas\ProjectStageForm;
use App\Filament\Resources\ProjectStages\Tables\ProjectStagesTable;
use App\Models\ProjectStage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectStageResource extends Resource
{
    protected static ?string $model = ProjectStage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $navigationLabel = 'Project Stages';

    protected static ?string $pluralModelLabel = 'Project Stages';

    protected static ?string $modelLabel = 'Project Stage';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $clusterBreadcrumb = 'cluster';

    public static function form(Schema $schema): Schema
    {
        return ProjectStageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectStagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectStages::route('/'),
            'create' => CreateProjectStage::route('/create'),
            'edit' => EditProjectStage::route('/{record}/edit'),
        ];
    }
}
