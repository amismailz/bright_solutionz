<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OurServiceResource\Pages;
use App\Filament\Resources\OurServiceResource\RelationManagers;
use App\Models\OurService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\DeleteAction;

use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\RichEditor;

class OurServiceResource extends Resource
{
    protected static ?string $model = OurService::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('Ranges & Points');
    }

    public static function getNavigationLabel(): string
    {
        return __('Our services');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Our services');
    }

    public static function getModelLabel(): string
    {
        return __('Service');
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
                    ->directory('our-services')
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
            'index' => Pages\ListOurServices::route('/'),
            'create' => Pages\CreateOurService::route('/create'),
            'edit' => Pages\EditOurService::route('/{record}/edit'),
        ];
    }
}
