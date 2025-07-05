<?php

namespace App\Filament\Resources;

use App\Enums\GenderEnum;
use App\Enums\RoleTypeEnum;
use App\Enums\StatusEnum;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Association;
use App\Models\Point;
use App\Models\Range;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getModelLabel(): string
    {
        return __('Users');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('role')
                    ->label(__('Role'))
                    ->options(RoleTypeEnum::labels())
                    ->required()
                  //  ->disabled(fn(?User $record) => filled($record))
                    ->reactive(),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label(__('Name'))
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label(__('Email'))
                    ->maxLength(255)
                    ->required()
                // ->visible(
                //     fn(Forms\Get $get) =>
                //     $get('role') === RoleTypeEnum::SuperAdmin->value
                // )
                ,

             

                Forms\Components\TextInput::make('password')
                    ->label(__('Password'))
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->required(fn(?User $record) => $record === null)
                    ->maxLength(255),

                Forms\Components\Select::make('gender')
                    ->label(__('Gender'))
                    ->options(collect(GenderEnum::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])),

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->label(__('Phone'))
                    ->maxLength(20),

            
            ])
            ->columns(3);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label(__('ID')),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable(),



                Tables\Columns\TextColumn::make('roles')
                    ->label(__('Role'))
                    ->badge()
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->isAdmin()) {
                            return RoleTypeEnum::labels()[RoleTypeEnum::SuperAdmin->value];
                        }

                        if ($record->isDistributor()) {
                            return RoleTypeEnum::labels()[RoleTypeEnum::Distributor->value];
                        }

                        if ($record->isSupervisor()) {
                            return RoleTypeEnum::labels()[RoleTypeEnum::Supervisor->value];
                        }

                        if ($record->isAdminSupervisor()) {
                            return RoleTypeEnum::labels()[RoleTypeEnum::AdminSupervisor->value];
                        }

                        return __('No Role');
                    })
                    ->color(function ($state, $record) {
                        return match (true) {
                            $record->isAdmin()       => 'success',
                            $record->isSupervisor()  => 'warning',
                            $record->isDistributor() => 'info',
                            default                  => 'gray',
                        };
                    }),

                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label(__('Gender'))
                    ->badge()
                    ->formatStateUsing(fn($state) => GenderEnum::from($state)->label())
                    ->color(fn($state) => $state === GenderEnum::Male ? 'info' : 'pink'),

           
            ])
            // ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ExportBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
