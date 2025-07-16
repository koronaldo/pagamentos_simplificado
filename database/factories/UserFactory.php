<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'cpfcnpj' => rand(0, 1) ? $this->gerarCpf() : $this->gerarCnpj(),
            'perfil' => rand(0, 1) ? 'cliente' : 'lojista',
            'password' => static::$password ??= Hash::make('12345678'),
            'saldo' => '1000',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    
    private function gerarCpf(bool $formatado = true): string
    {
        $n = array_map(fn() => mt_rand(0, 9), range(1, 9));
        $d1 = 11 - (array_sum(array_map(fn($v, $i) => $v * (10 - $i), $n, array_keys($n))) % 11);
        $d1 = $d1 >= 10 ? 0 : $d1;
        $n[] = $d1;
        
        $d2 = 11 - (array_sum(array_map(fn($v, $i) => $v * (11 - $i), $n, array_keys($n))) % 11);
        $d2 = $d2 >= 10 ? 0 : $d2;
        $n[] = $d2;
        
        $cpf = implode('', $n);
        
        if ($formatado) {
            return substr($cpf, 0, 3) . '.' .
                substr($cpf, 3, 3) . '.' .
                substr($cpf, 6, 3) . '-' .
                substr($cpf, 9, 2);
        }
        
        return $cpf;
    }
    
    private function gerarCnpj(bool $formatado = true): string
    {
        $n = array_map(fn() => mt_rand(0, 9), range(1, 8));
        array_push($n, 0, 0, 0, 1);
        
        $pesos1 = [5,4,3,2,9,8,7,6,5,4,3,2];
        $d1 = 11 - (array_sum(array_map(fn($v, $p) => $v * $p, $n, $pesos1)) % 11);
        $d1 = $d1 >= 10 ? 0 : $d1;
        $n[] = $d1;
        
        $pesos2 = [6,5,4,3,2,9,8,7,6,5,4,3,2];
        $d2 = 11 - (array_sum(array_map(fn($v, $p) => $v * $p, $n, $pesos2)) % 11);
        $d2 = $d2 >= 10 ? 0 : $d2;
        $n[] = $d2;
        
        $cnpj = implode('', $n);
        
        if ($formatado) {
            return substr($cnpj, 0, 2) . '.' .
                substr($cnpj, 2, 3) . '.' .
                substr($cnpj, 5, 3) . '/' .
                substr($cnpj, 8, 4) . '-' .
                substr($cnpj, 12, 2);
        }
        
        return $cnpj;
    }
}
