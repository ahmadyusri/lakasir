<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\PaymentMethodResource\Pages;
use App\Models\Tenants\PaymentMethod;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    use HasTranslatableResource;

    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->translateLabel()
                    ->columnSpanFull(),
                Section::make()
                    ->schema([
                        Checkbox::make('is_cash')->label(__('Is Cash'))->inline(),
                        Checkbox::make('is_debit')->label(__('Is Debit'))->inline(),
                        Checkbox::make('is_credit')->label(__('Is Credit'))->inline(),
                        Checkbox::make('is_wallet')->label(__('Is Wallet'))->inline(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                IconColumn::make('is_cash')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label(__('Is Cash')),
                IconColumn::make('is_debit')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label(__('Is Debit')),
                IconColumn::make('is_credit')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label(__('Is Credit')),
                IconColumn::make('is_wallet')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label(__('Is Wallet')),
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
