<?php

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'is_admin' =>  1,
                'level' => 1
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'is_admin' =>  0,
                'level' => 3
            ],
        ];

        Group::insert($data);
    }
}
