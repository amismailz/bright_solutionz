<?php

namespace App\Filament\Pages;

use App\Models\AboutUs;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AboutUsSettings extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static string $view = 'filament.pages.about-us-settings';
    protected static ?string $navigationLabel = 'About Us';

    // protected static ?string $title = 'About Us Settings';
    public ?AboutUs $about;
    public array $data = [];
    public function mount(): void
    {
        $this->about = AboutUs::firstOrCreate([]); // ✅ assign it to the class property

        // Optional: prefill the form using the model data
        $this->data = [
            'title' => $this->about->getTranslations('title'),
            'description' => $this->about->getTranslations('description'),
            'vision' => $this->about->getTranslations('vision'),
            'mission' => $this->about->getTranslations('mission'),
        ];

        $this->form->fill($this->about->toArray());
    }
    public function getTitle(): string
    {
        return __('About Us');
    }
    public static function getNavigationLabel(): string
    {
        return __('About Us');
    }



    public function form(Form $form): Form
    {
        return $form
            //->model($this->about)
            ->statePath('data')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('title.en')
                            ->label(__('Title') . ' (' . __('english') . ')')
                            ->required(),
                        Forms\Components\TextInput::make('title.ar')
                            ->label(__('Title') . ' (' . __('arabic') . ')')
                            ->required(),
                    ]),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\RichEditor::make('description.en')
                            ->label(__('Description') . ' (' . __('English') . ')')
                            ->required(),

                        Forms\Components\RichEditor::make('description.ar')
                            ->label(__('Description') . ' (' . __('Arabic') . ')')
                            ->required(),
                    ]),
                Forms\Components\Grid::make(2)
                    ->schema([
                        RichEditor::make('vision.en')
                            ->label(__('Vision') . ' (' . __('English') . ')')
                            ->required(),

                        RichEditor::make('vision.ar')
                            ->label(__('Vision') . ' (' . __('Arabic') . ')')
                            ->required(),
                    ]),
                Forms\Components\Grid::make(2)
                    ->schema([
                        RichEditor::make('mission.en')
                            ->label(__('Mission') . ' (' . __('English') . ')')
                            ->required(),

                        RichEditor::make('mission.ar')
                            ->label(__('Mission') . ' (' . __('Arabic') . ')')
                            ->required(),
                    ]),


            ]);
    }
    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin');
    }
    public function save(): void
    {
        try {
            try {

                $validated = Validator::make($this->data, [
                    'title.en' => ['required', 'string'],
                    'title.ar' => ['required', 'string'],
                    'description.en' => ['required'],
                    'description.ar' => ['required'],
                    'vision.en' => ['required'],
                    'vision.ar' => ['required'],
                    'mission.en' => ['required'],
                    'mission.ar' => ['required'],
                ])->validate();

                $form = $this->form->fill();

                $this->about->update($validated);
                $this->about->refresh();

                $this->data = [
                    'title' => $this->about->getTranslations('title'),
                    'description' => $this->about->getTranslations('description'),
                    'vision' => $this->about->getTranslations('vision'),
                    'mission' => $this->about->getTranslations('mission'),
                ];

                $this->form->fill($this->data);
                // $this->about->update($form->getState());

                Notification::make()
                    ->title(__('Saved successfully'))
                    ->success()
                    ->body(__('About Us updated successfully!'))
                    ->send();
            } catch (\Throwable $th) {

                dd($th);
                Notification::make()
                    ->title(__("We can't save the data, please contact the administrator"))
                    ->danger()
                    ->send();

                throw ValidationException::withMessages([
                    'errors' => [__("We can't save the data, please contact the administrator")],
                ]);
            }
            redirect()->back();
        } catch (ValidationException $e) {
            throw ValidationException::withMessages($e->errors());
        }
    }
}
