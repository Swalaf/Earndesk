<?php

namespace Database\Seeders;

use App\Models\BoostPackage;
use Illuminate\Database\Seeder;

class BoostPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Starter Boost',
                'description' => 'Perfect for new listings',
                'price' => 500,
                'duration_days' => 3,
                'features' => json_encode([
                    '2x visibility boost',
                    'Featured in category',
                    'Priority placement'
                ]),
                'is_active' => true,
                'is_featured' => false,
                'boost_multiplier' => 2.0,
            ],
            [
                'name' => 'Pro Boost',
                'description' => 'Best for serious sellers',
                'price' => 1500,
                'duration_days' => 7,
                'features' => json_encode([
                    '5x visibility boost',
                    'Featured on homepage',
                    'Top placement in search',
                    'Badge displayed',
                    'Analytics dashboard'
                ]),
                'is_active' => true,
                'is_featured' => true,
                'boost_multiplier' => 5.0,
            ],
            [
                'name' => 'Premium Boost',
                'description' => 'Maximum exposure',
                'price' => 3000,
                'duration_days' => 14,
                'features' => json_encode([
                    '10x visibility boost',
                    'Featured on homepage & category',
                    'Top placement guaranteed',
                    'Premium badge',
                    'Priority support',
                    'Analytics dashboard',
                    'Custom thumbnail'
                ]),
                'is_active' => true,
                'is_featured' => false,
                'boost_multiplier' => 10.0,
            ],
            [
                'name' => 'Ultimate Boost',
                'description' => 'For maximum results',
                'price' => 5000,
                'duration_days' => 30,
                'features' => json_encode([
                    '20x visibility boost',
                    'Permanent homepage feature',
                    'First in all searches',
                    'Ultimate badge',
                    'Dedicated support',
                    'Advanced analytics',
                    'Custom branding',
                    'Promotional emails'
                ]),
                'is_active' => true,
                'is_featured' => false,
                'boost_multiplier' => 20.0,
            ],
        ];

        foreach ($packages as $package) {
            BoostPackage::create($package);
        }
    }
}
