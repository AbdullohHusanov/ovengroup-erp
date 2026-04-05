<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Asosiy ma\'lumotlar')
                    ->columns(2)
                    ->schema([

                        TextInput::make('name')
                            ->label('Ism')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('surname')
                            ->label('Familiya')
//                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Telefon raqam')
                            ->required()
                            ->tel()
                            ->unique(ignoreRecord: true)
                            ->placeholder('+998901234567')
                            ->maxLength(20)
                            ->suffixAction(
                                Action::make('copyPhone')
                                    ->label('Nusxalash')
                                    ->icon('heroicon-o-clipboard-document')
                                    ->tooltip('Telefon raqamni nusxalash')
                                    ->action(function ($state, $livewire) {
                                        if (blank($state)) {
                                            Notification::make()
                                                ->title('Telefon raqam bo\'sh!')
                                                ->warning()
                                                ->send();
                                            return;
                                        }

                                        $livewire->js(
                                            "navigator.clipboard.writeText('" . addslashes($state) . "')
                                            .then(() => { $wire.dispatch('notify', { message: 'Nusxalandi!' }) })"
                                        );

                                        Notification::make()
                                            ->title('Telefon raqam nusxalandi!')
                                            ->success()
                                            ->send();
                                    })
                            ),

                        Select::make('role')
                            ->label('Lavozim (rol)')
                            ->required()
                            ->options([
                                'welder'   => 'Payvandchi',
                                'inspector'=> 'Tekshiruvchi',
                                'cleaner'  => 'Tozalovchi',
                                'stamper'  => 'Muhrlovchi',
                                'warehousekeeper' => 'Ombochi',
                                'accountant' => 'Xisobchi'
                            ])
                            ->default('xodim')
                            ->native(false)
                            ->searchable(),

                    ]),

                Section::make('Kirish ma\'lumotlari')
                    ->description('Xodim tizimga telefon va parol orqali kiradi.')
                    ->columns(1)
                    ->schema([

                        TextInput::make('password')
                            ->label('Parol')
                            ->password()
                            ->revealable()                  // 👁 ko'rsatish / yashirish tugmasi
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->minLength(6)
                            ->maxLength(64)
                            ->placeholder(
                                fn (string $operation) =>
                                $operation === 'edit' ? 'O\'zgartirish uchun yangi parol kiriting' : 'Parol kiriting'
                            )
                            ->hintAction(
                            // 🔑 Parol generator
                                Action::make('generatePassword')
                                    ->label('Parol yaratish')
                                    ->icon('heroicon-o-key')
                                    ->color('warning')
                                    ->action(function ($set) {
                                        $password = self::generatePassword();
                                        $set('password', $password);
                                        $set('password_plain', $password); // ko'rsatish uchun
                                    })
                            )
                            ->suffixAction(
                            // 📋 Parolni nusxalash
                                Action::make('copyPassword')
                                    ->label('Nusxalash')
                                    ->icon('heroicon-o-clipboard-document')
                                    ->tooltip('Parolni nusxalash')
                                    ->action(function ($state, $livewire) {
                                        if (blank($state)) {
                                            Notification::make()
                                                ->title('Parol bo\'sh!')
                                                ->warning()
                                                ->send();
                                            return;
                                        }

                                        $livewire->js(
                                            "navigator.clipboard.writeText('" . addslashes($state) . "')
                                            .then(() => { console.log('copied') })"
                                        );

                                        Notification::make()
                                            ->title('Parol nusxalandi!')
                                            ->success()
                                            ->send();
                                    })
                            ),
                    ]),
            ]);
    }
    /**
     * Kuchli tasodifiy parol yaratadi.
     * Harflar + raqamlar + belgilar aralash, lekin o'qish oson.
     */
    private static function generatePassword(int $length = 10): string
    {
        $letters  = 'abcdefghjkmnpqrstuvwxyz'; // chalkash harflar olib tashlandi (i, l, o)
        $numbers  = '23456789';                 // 0, 1 olib tashlandi
        $specials = '@#$%!';

        $password  = $letters[random_int(0, strlen($letters) - 1)];  // kamida 1 harf
        $password .= strtoupper($letters[random_int(0, strlen($letters) - 1)]); // kamida 1 katta harf
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];  // kamida 1 raqam
        $password .= $specials[random_int(0, strlen($specials) - 1)]; // kamida 1 belgi

        $all = $letters . strtoupper($letters) . $numbers . $specials;
        for ($i = 4; $i < $length; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }

        // Aralashtirish
        return str_shuffle($password);
    }
}
