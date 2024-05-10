<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                Section::make([
                    TextInput::make('role')
                        ->required(),
                    Select::make('employment_type')
                        ->options([
                            'Full-time' => 'Full-time',
                            'Part-time' => 'Part-time',
                            'Self-employed' => 'Self-employed',
                            'Freelance' => 'Freelance',
                            'Contract' => 'Contract',
                            'Internship' => 'Internship',
                            'Seasonal' => 'Seasonal',
                        ])
                        ->required(),
                    TextInput::make('company')
                        ->required(),
                    TextInput::make('location')
                        ->required(),
                    Select::make('location_type')
                        ->options([
                            'On-site' => 'On-site',
                            'Hybird' => 'Hybird',
                            'Remote' => 'Remote',
                        ])
                        ->required(),
                    Checkbox::make('currently_working')
                        ->live(),
                    DatePicker::make('start_date')
                        ->native(false),
                    DatePicker::make('end_date')
                        ->native(false)
                        ->afterOrEqual('start_date')
                        ->visible(fn (Get $get): bool => ! $get('currently_working')),
                    Textarea::make('description'),
                ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('role')
                    ->searchable(),
                TextColumn::make('company')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->since(),
                TextColumn::make('end_date')
                    ->since(),

            ])
            ->filters([
                //
            ])
            ->recordUrl(
                fn (Model $record): string => Pages\ViewExperience::getUrl([$record->id]),
            )
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
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
            'index' => Pages\ListExperiences::route('/'),
            'create' => Pages\CreateExperience::route('/create'),
            'view' => Pages\ViewExperience::route('/{record}'),
            'edit' => Pages\EditExperience::route('/{record}/edit'),
        ];
    }
}
