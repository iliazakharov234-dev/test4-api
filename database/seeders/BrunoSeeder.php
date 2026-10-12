<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BrunoSeeder extends Seeder
{
    /**
     * Предпосылки для Bruno-коллекции (запросы 3, 4, 5):
     *  - 3. POST /api/register ожидает, что dup@test.ru уже зарегистрирован (422 unique);
     *  - 4. POST /api/login ожидает, что login@test.ru существует (200);
     *  - 5. POST /api/login с неверным паролем ожидает 401 для login@test.ru.
     *
     * Идемпотентен (firstOrCreate) — безопасен для повторных запусков и CI.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'Dup User', 'email' => 'dup@test.ru'],
            ['name' => 'Login User', 'email' => 'login@test.ru'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                ['name' => $user['name'], 'password' => Hash::make('Qwerty12')]
            );
        }
    }
}
