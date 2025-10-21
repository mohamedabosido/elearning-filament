<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        SiteSetting::create([
            'ar' => [
                'name' => 'موقع تعليمي',
                'address' => '123 الشارع الرئيسي، المدينة، البلد',
            ],
            'en' => [
                'name' => 'Educational Site',
                'address' => '123 Main St, City, Country',
            ],
            'whatsapp' => '+62881080265310',
            'phone' => '+62881080265310',
            'email' => 'en@en.com',
            'facebook' => 'https://www.facebook.com/',
            'twitter' => 'https://www.twitter.com/',
            'instagram' => 'https://www.instagram.com/',
            'linkedin' => 'https://www.linkedin.com/',
            'youtube' => 'https://www.youtube.com/',
            'tiktok' => 'https://www.tiktok.com/',
        ]);

        $admin = User::factory()->create([
            'name' => 'Mohammed Abusidu',
            'email' => 'admin@admin.com',
            'phone' => '+62881080265310',
            'password' => Hash::make('12345678'),
        ]);
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $admin->assignRole($role);
    }
}
