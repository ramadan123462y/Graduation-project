<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $names = [
            [
                'name' => [
                    'en' => 'Fruit',
                    'ar' => 'الفواكه'
                ]
            ],
            [
                'name' => [
                    'en' => 'Vegetable',
                    'ar' => 'الخضروات'
                ]
            ],
            [
                'name' => [
                    'en' => 'Legumes',
                    'ar' => 'بقوليات'
                ]
            ],
            [
                'name' => [
                    'en' => 'Herbs',
                    'ar' => 'أعشاب'
                ]
            ],
        ];
    
        foreach ($names as $name) {
            \App\Models\Section::create($name);
        }
    }
}
