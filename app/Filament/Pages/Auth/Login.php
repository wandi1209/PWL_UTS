<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseAuth;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class Login extends BaseAuth
{
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('username')->required()->autofocus(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }
}