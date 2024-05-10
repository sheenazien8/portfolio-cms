<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
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
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                        ])
                        ->imageEditorMode(2)
                        ->required(),
                    FileUpload::make('gallery')
                        ->multiple(),
                ])
                    ->columnSpan(2),
                Section::make([
                    TextInput::make('slug')
                        ->required(),
                    Select::make('category_id')
                        ->label(__('Category'))
                        ->relationship(name: 'category', titleAttribute: 'name')
                        ->searchable()
                        ->required(),
                    Select::make('sub_category_id')
                        ->label(__('Sub Category'))
                        ->relationship(name: 'subCategory', titleAttribute: 'name')
                        ->searchable()
                        ->required(),
                    TagsInput::make('tags')
                        ->required(),
                    KeyValue::make('meta')
                        ->nullable()
                        ->label('Seo Meta'),
                ])
                    ->columnSpan(1),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('header_image')
                    ->disk('public'),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
            ])
            ->recordUrl(
                fn (Model $record): string => Pages\ViewPost::getUrl([$record->id]),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
