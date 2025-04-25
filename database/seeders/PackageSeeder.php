<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => ['en' => '10 Coins Package', 'ar' => 'باقه 10 كوينز'],
                'description' => ['en' => 'Recharge your account with 10 coins.', 'ar' => 'اشحن حسابك بـ 10 كوينز.'],
                'price' => 30.00,
                'coins' => 10
            ],
            [
                'name' => ['en' => '20 Coins Package', 'ar' => 'باقه 20 كوينز'],
                'description' => ['en' => 'Recharge your account with 20 coins.', 'ar' => 'اشحن حسابك بـ 20 كوينز.'],
                'price' => 80.00, 
                'coins' => 20
            ],
            [
                'name' => ['en' => '30 Coins Package', 'ar' => 'باقه 30 كوينز'],
                'description' => ['en' => 'Recharge your account with 30 coins.', 'ar' => 'اشحن حسابك بـ 30 كوينز.'],
                'price' => 150.00 , 
                'coins' => 30
            ],
            [
                'name' => ['en' => '50 Coins Package', 'ar' => 'باقه 50 كوينز'],
                'description' => ['en' => 'Recharge your account with 50 coins.', 'ar' => 'اشحن حسابك بـ 50 كوينز.'],
                'price' => 300.00 , 
                'coins' => 50
            ]
        ];
        
        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
