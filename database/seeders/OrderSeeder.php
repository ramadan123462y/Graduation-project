<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            ['name' => 'Lamiales'],
            ['name' => 'Solanales'],
            
            ['name' => 'Cucurbitales'],

            ['name' => 'Apiales'],
        ];

        foreach ($array as $item) {
            \App\Models\Order::create($item);
        }
    }
}
