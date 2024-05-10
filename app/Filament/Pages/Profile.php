<?php

namespace App\Filament\Pages;

use App\Models\Profile as ModelsProfile;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns;
use Filament\Pages\Page;

class Profile extends Page
{
    use Concerns\CanUseDatabaseTransactions;
    use Concerns\HasMaxWidth;
    use Concerns\HasTopbar;
    use Concerns\InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.pages.profile';

    public array $data = ['about_me' => '', 'skills' => []];

    public function mount()
    {
        $profile = ModelsProfile::first();
        if ($profile) {
            $this->form->fill($profile->toArray());
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Card::make([
                FileUpload::make('profile_picture')
                    ->image()
                    ->imageEditor()
                    ->imageResizeMode('cover')
                    ->acceptedFileTypes(['image/*'])
                    ->columnSpanFull(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('nickname')
                    ->required(),
                TagsInput::make('skills')
                    ->placeholder('comma (,) to add your skills')
                    ->required(),
                FileUpload::make('resume')
                    ->acceptedFileTypes(['application/pdf']),
                Fieldset::make('socials_button')
                    ->label('Social Button')
                    ->schema([
                        TextInput::make('socials_button.github')
                            ->url()
                            ->required(),
                        TextInput::make('socials_button.instagram')
                            ->url()
                            ->required(),
                        TextInput::make('socials_button.facebook')
                            ->url()
                            ->required(),
                    ]),
                RichEditor::make('about_me')
                    ->columnSpanFull(),
                Actions::make([
                    Action::make(__('Save'))
                        ->action('save'),
                ]),
            ])
                ->columns(2),
        ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $profile = ModelsProfile::first() ?? new ModelsProfile();
        $profile->fill($data);
        $profile->save();
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
