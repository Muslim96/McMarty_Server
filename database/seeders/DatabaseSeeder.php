<?php

namespace Database\Seeders;

use App\Models\FineReason;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'super_admin' => 'Суперадмин',
            'admin'       => 'Администратор',
            'manager'     => 'Менеджер ресторана',
            'accountant'  => 'Бухгалтер',
        ];
        foreach ($roles as $slug => $name) {
            Role::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name'     => 'Суперадмин',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe123!')),
                'role_id'  => Role::where('slug', 'super_admin')->value('id'),
                'is_active' => true,
            ]
        );

        $reasons = [
            ['Опоздание', 500], ['Прогул', 3000], ['Нарушение санитарных норм', 1500],
            ['Нарушение формы одежды', 300], ['Порча имущества', 0], ['Конфликт с клиентом', 1000],
        ];
        foreach ($reasons as [$name, $amount]) {
            FineReason::firstOrCreate(['name' => $name], ['default_amount' => $amount]);
        }
    }
}
