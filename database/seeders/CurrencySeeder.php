<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['country' => 'Argentina', 'currency' => 'Argentine Peso', 'code' => 'ARS', 'symbol' => '$', 'status' => '0'],
            ['country' => 'Australia', 'currency' => 'Australian Dollar', 'code' => 'AUD', 'symbol' => '$', 'status' => '0'],
            ['country' => 'Bangladesh', 'currency' => 'Bangladeshi Taka', 'code' => 'BDT', 'symbol' => '৳', 'status' => '0'],
            ['country' => 'Brazil', 'currency' => 'Brazilian Real', 'code' => 'BRL', 'symbol' => 'R$', 'status' => '0'],
            ['country' => 'Canada', 'currency' => 'Canadian Dollar', 'code' => 'CAD', 'symbol' => '$', 'status' => '0'],
            ['country' => 'Chile', 'currency' => 'Chilean Peso', 'code' => 'CLP', 'symbol' => '$', 'status' => '0'],
            ['country' => 'China', 'currency' => 'Chinese Yuan', 'code' => 'CNY', 'symbol' => '¥', 'status' => '0'],
            ['country' => 'Colombia', 'currency' => 'Colombian Peso', 'code' => 'COP', 'symbol' => '$', 'status' => '0'],
            ['country' => 'Czech Republic', 'currency' => 'Czech Koruna', 'code' => 'CZK', 'symbol' => 'Kč', 'status' => '0'],
            ['country' => 'Denmark', 'currency' => 'Danish Krone', 'code' => 'DKK', 'symbol' => 'kr', 'status' => '0'],
            ['country' => 'Egypt', 'currency' => 'Egyptian Pound', 'code' => 'EGP', 'symbol' => '£', 'status' => '0'],
            ['country' => 'European Union', 'currency' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'status' => '0'],
            ['country' => 'Hungary', 'currency' => 'Hungarian Forint', 'code' => 'HUF', 'symbol' => 'Ft', 'status' => '0'],
            ['country' => 'India', 'currency' => 'Indian Rupee', 'code' => 'INR', 'symbol' => '₹', 'status' => '0'],
            ['country' => 'Indonesia', 'currency' => 'Indonesian Rupiah', 'code' => 'IDR', 'symbol' => 'Rp', 'status' => '0'],
            ['country' => 'Israel', 'currency' => 'Israeli Shekel', 'code' => 'ILS', 'symbol' => '₪', 'status' => '0'],
            ['country' => 'Japan', 'currency' => 'Japanese Yen', 'code' => 'JPY', 'symbol' => '¥', 'status' => '0'],
            ['country' => 'Kenya', 'currency' => 'Kenyan Shilling', 'code' => 'KES', 'symbol' => 'KSh', 'status' => '0'],
            ['country' => 'Malaysia', 'currency' => 'Malaysian Ringgit', 'code' => 'MYR', 'symbol' => 'RM', 'status' => '0'],
            ['country' => 'Mexico', 'currency' => 'Mexican Peso', 'code' => 'MXN', 'symbol' => '$', 'status' => '0'],
            ['country' => 'New Zealand', 'currency' => 'New Zealand Dollar', 'code' => 'NZD', 'symbol' => '$', 'status' => '0'],
            ['country' => 'Nigeria', 'currency' => 'Nigerian Naira', 'code' => 'NGN', 'symbol' => '₦', 'status' => '0'],
            ['country' => 'Norway', 'currency' => 'Norwegian Krone', 'code' => 'NOK', 'symbol' => 'kr', 'status' => '0'],
            ['country' => 'Pakistan', 'currency' => 'Pakistani Rupee', 'code' => 'PKR', 'symbol' => '₨', 'status' => '0'],
            ['country' => 'Philippines', 'currency' => 'Philippine Peso', 'code' => 'PHP', 'symbol' => '₱', 'status' => '0'],
            ['country' => 'Poland', 'currency' => 'Polish Zloty', 'code' => 'PLN', 'symbol' => 'zł', 'status' => '0'],
            ['country' => 'Qatar', 'currency' => 'Qatari Riyal', 'code' => 'QAR', 'symbol' => '﷼', 'status' => '0'],
            ['country' => 'Russia', 'currency' => 'Russian Ruble', 'code' => 'RUB', 'symbol' => '₽', 'status' => '0'],
            ['country' => 'Saudi Arabia', 'currency' => 'Saudi Riyal', 'code' => 'SAR', 'symbol' => '﷼', 'status' => '0'],
            ['country' => 'Singapore', 'currency' => 'Singapore Dollar', 'code' => 'SGD', 'symbol' => '$', 'status' => '0'],
            ['country' => 'South Africa', 'currency' => 'South African Rand', 'code' => 'ZAR', 'symbol' => 'R', 'status' => '0'],
            ['country' => 'South Korea', 'currency' => 'South Korean Won', 'code' => 'KRW', 'symbol' => '₩', 'status' => '0'],
            ['country' => 'Sweden', 'currency' => 'Swedish Krona', 'code' => 'SEK', 'symbol' => 'kr', 'status' => '0'],
            ['country' => 'Switzerland', 'currency' => 'Swiss Franc', 'code' => 'CHF', 'symbol' => 'CHF', 'status' => '0'],
            ['country' => 'Thailand', 'currency' => 'Thai Baht', 'code' => 'THB', 'symbol' => '฿', 'status' => '0'],
            ['country' => 'Turkey', 'currency' => 'Turkish Lira', 'code' => 'TRY', 'symbol' => '₺', 'status' => '0'],
            ['country' => 'United Arab Emirates', 'currency' => 'UAE Dirham', 'code' => 'AED', 'symbol' => 'د.إ', 'status' => '0'],
            ['country' => 'United Kingdom', 'currency' => 'Pound Sterling', 'code' => 'GBP', 'symbol' => '£', 'status' => '0'],
            ['country' => 'United States', 'currency' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'status' => '1'],
            ['country' => 'Vietnam', 'currency' => 'Vietnamese Dong', 'code' => 'VND', 'symbol' => '₫', 'status' => '0'],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
