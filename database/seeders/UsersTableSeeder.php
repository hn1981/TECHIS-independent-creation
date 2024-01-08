<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
        // テーブルデータをクリア
        DB::table('users')->truncate();
        //都道府県データを作成
        $users = [
            ['id' => '01',
            'name' => 'test_admin001',
            'email' => 'test_admin001@example.com',
            'password' => '$2y$10$BngerdmIGSMMDYO2.UUlOuKsbtgGCuxlKi/RkdajoKGEsjH5XR6Ly',
            'role' => '1',
            ],
            ['id' => '02',
            'name' => 'test_user001',
            'email' => 'test001@example.com',
            'password' => '$2y$10$BngerdmIGSMMDYO2.UUlOuKsbtgGCuxlKi/RkdajoKGEsjH5XR6Ly',
            'role' => '0',
            ],
            ['id' => '03',
            'name' => 'test_user002',
            'email' => 'test002@example.com',
            'password' => '$2y$10$BngerdmIGSMMDYO2.UUlOuKsbtgGCuxlKi/RkdajoKGEsjH5XR6Ly',
            'role' => '0',
            ],
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
