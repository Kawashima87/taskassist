<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'さき_travel', 'yuri_ログ', 'Haruka_coding', 'しゅん_musicbox', 'misa_photo_diary', 
            'タクミ_devlog', 'kenta_メモ', 'nana_designroom', 'ai_ことば帳', 'ひかる_foodie',
            'みお_codingdays', 'ren_musiclife', 'Shun_techmemo', 'alex.devnotes', 'clara.musicbox',
            'ゆり_cafejournal', 'Saki_日々ノート'
        ];

        $icons = [
            'azarashi.svg', 'man.svg', 'hamburger.svg',
            'mountain.svg', 'tie.svg', 'penguin.svg',
            'woman.svg', 'baseball.svg', 'fox.svg',
            'eagle.svg', 'strawberry.svg', 'crown.svg'
        ];

        foreach ($names as $i => $name) {
            User::create([
                'name' => $name,
                'email' => "user{$i}@example.com",
                'password' => Hash::make('11111111'),
                'icon_path' => 'icons/' . $icons[$i % count($icons)],
            ]);
        }
    }
}
