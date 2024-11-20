<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Wishlist', 'color' => '#718096', 'order' => 1],
            ['name' => 'Applied', 'color' => '#4299E1', 'order' => 2],
            ['name' => 'Phone Screen', 'color' => '#48BB78', 'order' => 3], //example: recruiter call/chat the candidate
            ['name' => 'Interview', 'color' => '#805AD5', 'order' => 4],
            ['name' => 'Offer', 'color' => '#38B2AC', 'order' => 5],
            ['name' => 'Accepted', 'color' => '#38A169', 'order' => 6],
            ['name' => 'Rejected', 'color' => '#E53E3E', 'order' => 7],
            ['name' => 'Withdrawn', 'color' => '#718096', 'order' => 8],
        ];

        foreach ($statuses as $status) {
            \App\Models\ApplicationStatus::create($status);
        }
    }
}
