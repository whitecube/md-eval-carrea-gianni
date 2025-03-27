<?php

namespace Database\Seeders;

use App\Models\User;
use App\Transactions\Clients\ClientType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $friandises = User::factory()->create([
            'name' => 'Les friandises de Camille',
            'email' => 'friandises@example.com',
        ]);
        $friandises->client()->create([
            'type' => ClientType::Normal,
        ]);

        $carrefour = User::factory()->create([
            'name' => 'Carrefour Market',
            'email' => 'carrefour@example.com',
        ]);
        $carrefour->client()->create([
            'type' => ClientType::Wholesaler,
        ]);
        $carrefour->supplier()->create([]);

        $fries = User::factory()->create([
            'name' => 'La friterie du coin',
            'email' => 'friterie@example.com',
        ]);
        $fries->client()->create([
            'type' => ClientType::Vip,
        ]);
    }
}
