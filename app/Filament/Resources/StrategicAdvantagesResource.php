<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrategicAdvantagesResource\Pages;
use App\Filament\Resources\StrategicAdvantagesResource\RelationManagers;
use App\Models\StrategicAdvantage;
use App\Models\StrategicAdvantages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\RichEditor;

class StrategicAdvantagesResource extends Resource
{
    protected static ?string $model = StrategicAdvantage::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationGroup(): ?string
    {
        return __('Ranges & Points');
    }

    public static function getNavigationLabel(): string
    {
        return __('Strategic Advantages');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Strategic Advantages');
    }

    public static function getModelLabel(): string
    {
        return __('Strategic Advantage');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Title') . ' (' . __('english') . ')')
                    ->required(),
                Forms\Components\TextInput::make('title.ar')
                    ->label(__('Title') . ' (' . __('arabic') . ')')
                    ->required(),
                RichEditor::make('description.en')
                    ->label(__('Description') . ' (' . __('English') . ')')
                    ->required(),

                RichEditor::make('description.ar')
                    ->label(__('Description') . ' (' . __('Arabic') . ')')
                    ->required(),

                FileUpload::make('image')
                    ->label(__('Image'))
                    ->image()
                    ->directory('strategic-advantages')
                    ->disk('public')
                    ->visibility('public')
                    ->required()
                    ->imagePreviewHeight('100'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label(__('ID')),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime('d M, Y H:i:s')
                    ->sortable()
                    ->tooltip(fn($record) => $record->created_at?->format('Y-m-d H:i:s') ?? __('No Date')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListStrategicAdvantages::route('/'),
            'create' => Pages\CreateStrategicAdvantages::route('/create'),
            'edit' => Pages\EditStrategicAdvantages::route('/{record}/edit'),
        ];
    }
}
