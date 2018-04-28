<?php

namespace App\Http\Controllers;

use App\UserSites;
use Illuminate\Http\Request;
use Goutte;

class ScrapeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test(){
   
    $crawler = Goutte::request('GET', 'http://buyee.jp/item/search/query/porter%20coppi');
    
    $resNode = $crawler->filter('.product_whole')->each(function ($node) {
        $res = array();
        $res['title'] = trim($node->filter('.product_title')->text());
        $res['price'] = $node->filter('.product_price')->text();
        $res['url'] = 'buyee.jp'.trim($node->filter('a')->attr('href'));
        return $res;
    });
    print_r($resNode);
    return view('welcome');
    }

    public function funko()
    {
        $crawler = Goutte::request('GET', env('FUNKO_POP_URL'));

            $crawler->filter('.product_info')->each(function ($node) {
                $sku   = trim($node->filter('.product_title')->text());
                $title = trim($node->filter('.product_price')->text());
                print_r($sku.', '.$title);
            });

            //$crawler->filter('.product_info')->each(function ($node) {
                //$sku   = trim($node->filter('.product_price')->text());
                //$title = trim($node->filter('.product_title')->text());

               // print_r($node->text());
           // });

        return true;
    }

    public function crawl(){
        $siteitems = UserSites::all();
        dd($siteitems);
    }
}
