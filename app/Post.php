<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    
	 // reequired for softdelete functionality
	use SoftDeletes;
	Protected $dates = ['deleted_at'];

	// required for "mass assignment" using the create mothod.
	Protected $fillable = [
		'title',
		'content'
	];



	public function user(){

		 return $this->belongsTo('App\User');

	}

	public function photos(){

		return $this->morphMany('App\Photo', 'imageable');

	}

	
	//Get all of the tags for the post.
	public function tags(){

		return $this->morphToMany('App\Tag','tagable');

	}

}	
