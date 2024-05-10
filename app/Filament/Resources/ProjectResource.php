<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    RichEditor::make('body')
                        ->required(),
                    FileUpload::make('header_image')
                        ->image()
                        ->imageEditor()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '1:1',
                            '4:3',
                            '16:9',
                        ])
                        ->imageEditorMode(2)
                        ->acceptedFileTypes(['image/*', 'video/*'])
                        ->required(),
                    FileUpload::make('gallery')
                        ->multiple(),
                ])
                    ->columnSpan(2),
                Section::make([
                    TextInput::make('slug')
                        ->required(),
                    Select::make('role')
                        ->options([
                            'Backend Developer' => 'Backend Developer',
                            'Fullstack Developer' => 'Fullstack Developer',
                            'Frontend Developer' => 'Frontend Developer',
                        ])
                        ->required(),
                    TagsInput::make('tags')
                        ->required(),
                    TagsInput::make('tools')
                        ->required(),
                    TextInput::make('url')
                        ->url()
                        ->required(),
                    KeyValue::make('meta')
                        ->nullable()
                        ->label('Seo Meta'),
                ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('header_image')
                    ->disk('public'),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable(),
            ])
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
