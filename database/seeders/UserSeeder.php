<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        
        User::create([
            'name' => 'Cliente 1',
            'email' => '1@cliente.com.br',
            'perfil' => 'cliente',
            'cpfcnpj' => '11',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'saldo' => '1000'            
        ]);
        
        
        User::create([
            'name' => 'Cliente 2',
            'email' => '2@cliente.com.br',
            'perfil' => 'cliente',
            'cpfcnpj' => '12',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'saldo' => '1000'
        ]);
        
        User::create([
            'name' => 'Cliente 3',
            'email' => '3@cliente.com.br',
            'perfil' => 'cliente',
            'cpfcnpj' => '13',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'saldo' => '1000'
        ]);
        
        User::create([
            'name' => 'Cliente 4',
            'email' => '4@cliente.com.br',
            'perfil' => 'cliente',
            'cpfcnpj' => '14',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'saldo' => '1000'
        ]);
        
        User::create([
            'name' => 'Logista 1',
            'email' => '1@lojista.com.br',
            'perfil' => 'lojista',
            'cpfcnpj' => '21',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'saldo' => '1000'
        ]);
        
        User::create([
            'name' => 'Logista 2',
            'email' => '2@lojista.com.br',
            'perfil' => 'lojista',
            'cpfcnpj' => '22',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'saldo' => '1000'
        ]);
        
        User::create([
            'name' => 'Logista 3',
            'email' => '3@lojista.com.br',
            'perfil' => 'lojista',
            'cpfcnpj' => '23',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'saldo' => '1000'
        ]);
        
        User::create([
            'name' => 'Logista 4',
            'email' => '4@lojista.com.br',
            'perfil' => 'lojista',
            'cpfcnpj' => '24',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'saldo' => '1000'
        ]);
       
        
        
    }
}
