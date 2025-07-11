<?php

namespace Database\Seeders;

use App\Models\TimeZone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimezonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timezones = [
            ['country' => 'Afghanistan', 'timezone' => 'Asia/Kabul', 'utc_offset' => '+04:30'],
            ['country' => 'Australia', 'timezone' => 'Australia/Sydney', 'utc_offset' => '+10:00'],
            ['country' => 'Bangladesh', 'timezone' => 'Asia/Dhaka', 'utc_offset' => '+06:00'],
            ['country' => 'Brazil', 'timezone' => 'America/Sao_Paulo', 'utc_offset' => '-03:00'],
            ['country' => 'Canada', 'timezone' => 'America/Toronto', 'utc_offset' => '-05:00'],
            ['country' => 'China', 'timezone' => 'Asia/Shanghai', 'utc_offset' => '+08:00'],
            ['country' => 'France', 'timezone' => 'Europe/Paris', 'utc_offset' => '+01:00'],
            ['country' => 'Germany', 'timezone' => 'Europe/Berlin', 'utc_offset' => '+01:00'],
            ['country' => 'India', 'timezone' => 'Asia/Kolkata', 'utc_offset' => '+05:30'],
            ['country' => 'Indonesia', 'timezone' => 'Asia/Jakarta', 'utc_offset' => '+07:00'],
            ['country' => 'Japan', 'timezone' => 'Asia/Tokyo', 'utc_offset' => '+09:00'],
            ['country' => 'Mexico', 'timezone' => 'America/Mexico_City', 'utc_offset' => '-06:00'],
            ['country' => 'Russia', 'timezone' => 'Europe/Moscow', 'utc_offset' => '+03:00'],
            ['country' => 'Saudi Arabia', 'timezone' => 'Asia/Riyadh', 'utc_offset' => '+03:00'],
            ['country' => 'South Africa', 'timezone' => 'Africa/Johannesburg', 'utc_offset' => '+02:00'],
            ['country' => 'South Korea', 'timezone' => 'Asia/Seoul', 'utc_offset' => '+09:00'],
            ['country' => 'Spain', 'timezone' => 'Europe/Madrid', 'utc_offset' => '+01:00'],
            ['country' => 'Switzerland', 'timezone' => 'Europe/Zurich', 'utc_offset' => '+01:00'],
            ['country' => 'Thailand', 'timezone' => 'Asia/Bangkok', 'utc_offset' => '+07:00'],
            ['country' => 'United Arab Emirates', 'timezone' => 'Asia/Dubai', 'utc_offset' => '+04:00'],
            ['country' => 'United Kingdom', 'timezone' => 'Europe/London', 'utc_offset' => '+00:00'],
            ['country' => 'United States', 'timezone' => 'America/New_York', 'utc_offset' => '-05:00'],
            ['country' => 'Argentina (West)', 'timezone' => 'America/Argentina/Mendoza', 'utc_offset' => '-03:00'],
            ['country' => 'Nigeria', 'timezone' => 'Africa/Lagos', 'utc_offset' => '+01:00'],
            ['country' => 'Pakistan', 'timezone' => 'Asia/Karachi', 'utc_offset' => '+05:00'],
            ['country' => 'Nepal', 'timezone' => 'Asia/Kathmandu', 'utc_offset' => '+05:45'],
            ['country' => 'Sri Lanka', 'timezone' => 'Asia/Colombo', 'utc_offset' => '+05:30'],
            ['country' => 'Iraq', 'timezone' => 'Asia/Baghdad', 'utc_offset' => '+03:00'],
            ['country' => 'Iran', 'timezone' => 'Asia/Tehran', 'utc_offset' => '+03:30'],
            ['country' => 'Iceland', 'timezone' => 'Atlantic/Reykjavik', 'utc_offset' => '+00:00'],
            ['country' => 'New Zealand', 'timezone' => 'Pacific/Auckland', 'utc_offset' => '+12:00'],
            ['country' => 'Philippines', 'timezone' => 'Asia/Manila', 'utc_offset' => '+08:00'],
            ['country' => 'Malaysia', 'timezone' => 'Asia/Kuala_Lumpur', 'utc_offset' => '+08:00'],
            ['country' => 'Vietnam', 'timezone' => 'Asia/Ho_Chi_Minh', 'utc_offset' => '+07:00'],
            ['country' => 'Hong Kong', 'timezone' => 'Asia/Hong_Kong', 'utc_offset' => '+08:00'],
            ['country' => 'Taiwan', 'timezone' => 'Asia/Taipei', 'utc_offset' => '+08:00'],
            ['country' => 'Ukraine', 'timezone' => 'Europe/Kyiv', 'utc_offset' => '+02:00'],
            ['country' => 'Netherlands', 'timezone' => 'Europe/Amsterdam', 'utc_offset' => '+01:00'],
            ['country' => 'Italy', 'timezone' => 'Europe/Rome', 'utc_offset' => '+01:00'],
            ['country' => 'Portugal', 'timezone' => 'Europe/Lisbon', 'utc_offset' => '+00:00'],
            ['country' => 'Turkey', 'timezone' => 'Europe/Istanbul', 'utc_offset' => '+03:00'],
            ['country' => 'USA (West)', 'timezone' => 'America/Los_Angeles', 'utc_offset' => '-08:00'],
            ['country' => 'USA (Central)', 'timezone' => 'America/Chicago', 'utc_offset' => '-06:00'],
            ['country' => 'USA (Mountain)', 'timezone' => 'America/Denver', 'utc_offset' => '-07:00'],
            ['country' => 'Canada (West)', 'timezone' => 'America/Vancouver', 'utc_offset' => '-08:00'],
        ];

        foreach ($timezones as $timezone) {
            TimeZone::create($timezone);
        }
    }
}
