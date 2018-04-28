<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class UserSites extends Model
{
    protected $fillable = ['user_id','url','scrape_data','page_string','singlepage'];

    public function user(){

    	return $this->belongsTo('App\User');

    }
}
