<?php

namespace App\Filament\Tenant\Resources;

use App\Features\Role;
use App\Filament\Tenant\Resources\UserResource\Pages;
use App\Models\Tenants\User;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use HasTranslatableResource;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.name.label'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.email.label'))
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->required(),
                TextInput::make('profile.phone')
                    ->label(__('Phone Number')),
                TextInput::make('profile.address')
                    ->label(__('Address')),
                TextInput::make('password')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.password.label'))
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->rules('confirmed'),
                TextInput::make('password_confirmation')
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->label(__('filament-panels::pages/auth/edit-profile.form.password_confirmation.label')),
                Select::make('roles')
                    ->label(__('Roles'))
                    ->default(1)
                    ->visible(hasFeatureAndPermission(Role::class))
                    ->relationship('roles', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return User::query()->whereNot('id', auth()->id());
            })
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.name.label'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.email.label'))
                    ->searchable(),
                TextColumn::make('profile.phone')
                    ->searchable()
                    ->label(__('Phone Number')),
                TextColumn::make('profile.address')
                    ->label(__('Address')),
                TextColumn::make('roles.0.name')
                    ->visible(hasFeatureAndPermission(Role::class))
                    ->label(__('Role')),
                IconColumn::make('is_owner')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label(__('Is Owner')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
