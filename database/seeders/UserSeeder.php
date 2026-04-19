<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $master = User::updateOrCreate(
            ['username' => 'jaringan'],
            [
                'name' => 'JARINGAN UP3 CIANJUR',
                'email' => 'jaringan@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin_master',
                'cabang' => 'UP3 CIANJUR',
                'admin_cabang_id' => null,
            ]
        );

        $kota2 = User::updateOrCreate(
            ['username' => 'kota2'],
            [
                'name' => 'KOTA DUA',
                'email' => 'kota2@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin_cabang',
                'cabang' => 'KOTA',
                'admin_cabang_id' => null,
            ]
        );

        $mande2 = User::updateOrCreate(
            ['username' => 'mande2'],
            [
                'name' => 'MANDE DUA',
                'email' => 'mande2@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin_cabang',
                'cabang' => 'MANDE',
                'admin_cabang_id' => null,
            ]
        );

        $cipanas2 = User::updateOrCreate(
            ['username' => 'cipanas2'],
            [
                'name' => 'CIPANAS DUA',
                'email' => 'cipanas2@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin_cabang',
                'cabang' => 'CIPANAS',
                'admin_cabang_id' => null,
            ]
        );

        $sukanagara2 = User::updateOrCreate(
            ['username' => 'sukanagara2'],
            [
                'name' => 'SUKANAGARA DUA',
                'email' => 'sukanagara2@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin_cabang',
                'cabang' => 'SUKANAGARA',
                'admin_cabang_id' => null,
            ]
        );

        $tanggeung2 = User::updateOrCreate(
            ['username' => 'tanggeung2'],
            [
                'name' => 'TANGGEUNG DUA',
                'email' => 'tanggeung2@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin_cabang',
                'cabang' => 'TANGGEUNG',
                'admin_cabang_id' => null,
            ]
        );

        User::updateOrCreate(
            ['username' => 'kota52'],
            [
                'name' => 'KOTA LIMA DUA',
                'email' => 'kota52@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'cabang' => 'KOTA',
                'admin_cabang_id' => $kota2->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'mande52'],
            [
                'name' => 'MANDE LIMA DUA',
                'email' => 'mande52@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'cabang' => 'MANDE',
                'admin_cabang_id' => $mande2->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'cipanas52'],
            [
                'name' => 'CIPANAS LIMA DUA',
                'email' => 'cipanas52@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'cabang' => 'CIPANAS',
                'admin_cabang_id' => $cipanas2->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'sukanagara52'],
            [
                'name' => 'SUKANAGARA LIMA DUA',
                'email' => 'sukanagara52@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'cabang' => 'SUKANAGARA',
                'admin_cabang_id' => $sukanagara2->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'tanggeung52'],
            [
                'name' => 'TANGGEUNG LIMA DUA',
                'email' => 'tanggeung52@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'cabang' => 'TANGGEUNG',
                'admin_cabang_id' => $tanggeung2->id,
            ]
        );
    }
}