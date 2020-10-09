<?php

namespace Database\Seeders;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(1);

        if (!$user)
        {
            $user = User::factory()->create([
                'email' => 'test@mail.net'
            ]);
        }

        Quote::factory()->count(64)->create([
            'user_id' => $user->id
        ]);
    }
}
