<?php

use Illuminate\Database\Seeder;

class CreateUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create(['name'=>'Admin','email'=>'admin@163.com','password'=>bcrypt('admin'),'picture'=>'/img/larry.jpg']);
    }
}
