<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    
	public function posts(){

		return $this->hasManyThrough('App\Post', 'App\User', 'country_id', 'user_id');
		// if we want to add the column name this is the fourth field - but its actually added by default. 
		//return $this->hasManyThrough('App\Post', 'App\User');


	}



}
