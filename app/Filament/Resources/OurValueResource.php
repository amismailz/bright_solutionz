<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OurValueResource\Pages;
use App\Filament\Resources\OurValueResource\RelationManagers;
use App\Models\OurValue;
use Filament\Actions\DeleteAction;
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

class OurValueResource extends Resource
{
    protected static ?string $model = OurValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationGroup(): ?string
    {
        return __('Ranges & Points');
    }

    public static function getNavigationLabel(): string
    {
        return __('Our Values');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Our Values');
    }

    public static function getModelLabel(): string
    {
        return __('value');
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
                    ->directory('our-values')
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
            'index' => Pages\ListOurValues::route('/'),
            'create' => Pages\CreateOurValue::route('/create'),
            'edit' => Pages\EditOurValue::route('/{record}/edit'),
        ];
    }
}
