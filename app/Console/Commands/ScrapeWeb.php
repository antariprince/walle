<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapeWeb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:site';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape websites';

    protected $collections = [
    'animation',
    'disney',
    'games',
    'heroes',
    'marvel',
    'monster-high',
    'movies',
    'pets',
    'rocks',
    'sports',
    'star-wars',
    'television',
    'the-vault',
    'the-vote',
    'ufc',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->scrape();
        // foreach ($collections as $collection) {
        //     $this->scrape($collection);
        // }
    }

    public static function scrape()
    {
        $crawler = Goutte::request('GET', env('FUNKO_POP_URL'));

        $pages = ($crawler->filter('footer .pagination li')->count() > 0)
            ? $crawler->filter('footer .pagination li:nth-last-child(2)')->text()
            : 0
        ;

        for ($i = 0; $i < $pages + 1; $i++) {
            if ($i != 0) {
                $crawler = Goutte::request('GET', env('FUNKO_POP_URL'));
            }

            $crawler->filter('.product_info')->each(function ($node) {
                $sku   = trim($node->filter('.product_price')->text());
                $title = trim($node->filter('.product_title')->text());

                print_r($sku.', '.$title);
            });
        }

        return true;
    }
}
