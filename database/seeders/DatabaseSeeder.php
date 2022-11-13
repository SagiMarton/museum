<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $users_count = rand(8,12);
        $users = collect();
        $password = bcrypt('password');
        for($i = 1; $i <= $users_count; $i++)
        {
            $users->add(\App\Models\User::factory()->create([
                'email' => 'user' . $i . '@szerveroldali.hu',
                'password' => $password,
            ]));

        }
        $users->add(\App\Models\User::factory()->create([
            'email' => 'admin@szerveroldali.hu',
            'password' => bcrypt('adminpwd'),
            'is_admin' => true,
        ]));

        $items = \App\Models\Item::factory(rand(10,15))->create();
        $labels = \App\Models\Label::factory(rand(5,10))->create();
        $comments = \App\Models\Comment::factory(rand(7,15))->create();

        $items->each(function ($item) use(&$labels) {
            $item->labels()->sync($labels->random(rand(1,$labels->count())));
        });

        $comments->each(function ($comment) use(&$users, &$items) {
            $comment->user()->associate($users->random())->save();
            $comment->item()->associate($items->random())->save();
        });




        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
