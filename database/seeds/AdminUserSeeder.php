<?php

use Illuminate\Database\Seeder;

/**
 * Class AdminUserSeeder
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            factory(App\User::class)->create([
                    "name" => env('ADMIN_USER', "teejay"),
                    "email" => env('ADMIN_EMAIL', "teejay.hidalgo@gmail.com"),
                    "password" => bcrypt(env('ADMIN_PWD', 'passw0rd'))]);
        } catch (\Illuminate\Database\QueryException $exception) {
        }
    }
}
