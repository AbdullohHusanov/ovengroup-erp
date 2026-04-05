<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login;
use Filament\Forms\Components;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;

class CustomLogin extends Login
{
    protected function getEmailFormComponent(): Components\TextInput
    {
        return TextInput::make('phone')
            ->label('Telefon raqam')
            ->placeholder('+998 90 123 45 67')
            ->tel()
            ->required()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'phone'    => $data['phone'],
            'password' => $data['password'],
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Tizimga kirish';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Tizimga kirish';
    }
}
