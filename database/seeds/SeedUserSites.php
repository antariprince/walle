<?php

use Illuminate\Database\Seeder;
use App\UserSites;
class SeedUserSites extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url1 = ['user_id' => 1, 'url' => 'http://buyee.jp/item/search/query/evo%20ssd%20500gb'];
        UserSites::create($url1);
        $url2 = ['user_id' => 1, 'url' => 'http://buyee.jp/item/search/query/porter%20coppi'];
        UserSites::create($url2);
    }
}
