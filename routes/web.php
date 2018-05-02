<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
    Route::get('sites', 'UserSitesController@index')->name('admin.sites');

    Route::get('sites/create', 'UserSitesController@create')->name('admin.sites.create');
    Route::post('sites/store', 'UserSitesController@store')->name('admin.sites.store');

    Route::get('sites/edit/{id}', 'UserSitesController@edit')->name('admin.sites.edit');
    Route::post('sites/update/{id}', 'UserSitesController@update')->name('admin.sites.update');
    Route::get('sites/preview/{id}', 'UserSitesController@preview')->name('admin.sites.preview');
    Route::get('sites/downloadcsv/{id}', 'UserSitesController@downloadCsv')->name('admin.sites.downloadcsv');
    Route::get('sites/getjson/{id}', 'UserSitesController@getJson')->name('admin.sites.getjson');

    Route::get('sites/destroy/{id}', 'UserSitesController@destroy')->name('admin.sites.destroy');

    Route::get('about', function ()    {
        $data = [];
        return view('about',$data);
    })->name('about');

    Route::get('home', [
        'uses' => 'HomeController@index',
        'as' => 'admin.home'
    ]);

});

Route::get('/scrape', [
	'uses' => 'ScrapeController@test',
	'as' => 'scrape.test'
]);

Route::get('/funko', [
    'uses' => 'ScrapeController@funko',
    'as' => 'scrape.funko'
]);

Route::get('crawl', 'ScrapeController@crawl')->name('crawl');


