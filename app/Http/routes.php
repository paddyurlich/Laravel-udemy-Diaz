<?php

use App\Post; //this is pointing the the Post model so that we can use all of the functions available under the Post Class and also the Model class becuase the model Class is extended into Post. 
use App\User;
use App\Country;
use App\Photo;
use App\Tag;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contact', function () {
    return "Hi I am the contact page";
});

//Route::get('/post/{id}', 'PostsController@index');

Route::resource('posts','PostsController');

Route::get('/contact','PostsController@contact');

Route::get('/post/{id}','PostsController@show_post');

Route::get('/insert',function(){

	 DB::insert('insert into posts(title, content) values(?,?)',['Sick today with Laravel','Laravel is great']);

	//DB::insert('insert into users (id, name) values (?, ?)', array(1, 'Dayle'));
});

Route::get('/read',function(){

	$results = DB::select('select * from posts where id = ?', [1]);
	//d($results);
	foreach($results as $post){
		return $post->title;
		//return $post->content;
	}
});



Route::get('/update',function(){
	$updated = DB::update('update posts set title = "Updated title" where id =?', [1]);
	return $updated;
});


Route::get('/delete',function(){
	$deleted = DB::delete('delete from posts where id = ?', [1]);
	return $deleted;
});


/*
|--------------------------------------------------------------------
| ELOQUENT
|--------------------------------------------------------------------
*/

//remeber that we have "use App\Post;" at the top of the page -> so the all functions in the "model" are available for us to use. 

Route::get('/read',function(){

	$posts = Post::all();

	foreach($posts as $post) {
		echo $post->title."</br>";
	}

});


Route::get('/find',function(){

	$post = Post::find(2);

	return $post->title."</br>";

});



Route::get('/findwhere',function(){
	$posts = Post::where('id',2)->orderBy('id','desc')->take(1)->get();

	return $posts;

});


Route::get('/findmore', function() {
	$posts = Post::findORFail(2);
	return $posts;

});

Route::get('/findsomemore', function() {
 $posts = Post::where('users_count', '<', 50)->firstOrFail();

});

Route::get('/basicinsert',function() {
 $post = new Post;

 $post->title = 'New title using ORM / eloquent';
 $post->content = 'New content of post';

 $post->save();

});

Route::get('/findinsert2',function() {
 $post = Post::find(2);

 $post->title = 'Updated title for Record Id 2';
 $post->content = 'Updated content for record ID 2';

 $post->save();

});

Route::get('/create',function(){

	Post::create(['title'=>'New title using the Create mothod','content'=>'New content using the create mothod']);
	//Post::create(['content'=>'New content using the create mothod']);

});

Route::get('/update', function(){

	Post::where('id',2)->where('is_admin','0')->update(['title'=>'this is an updated title because admin is 0']);

});


Route::get('/delete1',function(){

 $post =post::find(5);

 $post->delete();

});



Route::get('/delete2',function(){

	Post::destroy([8,9]);

});



Route::get('/delete3',function(){


	post::where('is_admin',0)->delete();

});


Route::get('/softdelete',function(){
// note that the soft delete function in the posts model 

	post::find(7)->delete();

});



Route::get('/readonlytrashed',function(){
// read only the trashed posts
// note that the soft delete function in the posts model 

	$post = Post::onlyTrashed()->get();
	//$post = Post::onlyTrashed()->where('is_admin',0)->get();
	return $post;

});

Route::get('/readtrashedandnontrashed',function(){
// read trashed and non trashed posts
// note that the soft delete function in the posts model 

	$post = Post::withTrashed()->get();
	//$post = Post::withTrashed()->where('is_admin',0)->get();
	return $post;

});

Route::get('/restore',function(){

 post::withTrashed()->restore();
 //post::withTrashed()->where('id',0)->restore();

});

Route::get('/forcedelete',function(){

	//post::where('id',7)->forcedelete();
	//post::find(7)->forcedelete();
	post::onlyTrashed()->where('is_admin',0)->forcedelete();

});

/*
|--------------------------------------------------------------------
| ELOQUENT relationships
|--------------------------------------------------------------------
*/

//remeber that we have "use App\User;" at the top of the page -> so the all functions in the "model" are available for us to use. 

// ------ One to one / has one relationship

Route::get('/user/{id}/post',function($id){

 return User::find($id)->post->title;


});


Route::get('/post/{id}/user',function($id){

return Post::find($id)->user->name;

});

Route::get('/posts',function(){

	$user=user::find(1,['id']);
	//$user=user::find(1);

	foreach ($user->posts as $post) {
		echo $post->title."<br/>";
	}

});


//------ many to many relationships


Route::get('/user/{id}/role',function($id){

	 ///$user = user::find($id)->roles;
	$user = user::find($id);

	foreach($user->roles as $role) {
 		return $role->name."<br/>";
 	}

});



 // Route::get('/user/{id}/roles',function($id){

	// //$user = user::find($id)->roles;
	// $user = user::find($id)->roles()->orderBy('id','desc')->get();

	// foreach($user as $role) {
 // 		return $role."<br/>";
 // }
	
//});




 //--------- Accessing the intermediate table / pivot table

Route::get('user/pivot', function(){
	$user = User::find(2);

	foreach($user->roles as $role){
		//echo $role->pivot->role_id;
		echo $role->pivot;
	}


});




//-------- has many through relationship

Route::get('/user/country/{id}', function($id){
// this will give us all post titles that belong to a particular country code.  
//so we are providing the country code when then looks up the "user_id" which then return all posts with that "user_id". 

	$country = Country::find($id);

	foreach($country->posts as $post) {
			echo $post->title."<br/>";

	}

});

			 



//-------- polymorphic relationship



Route::get('/user/{id}/photos', function($id){

//when the user id is provided - return all user photos related to that user

	$user=User::find($id);

	foreach($user->photos as $photo) {
		return $photo;
	}

});


Route::get('/post/{id}/photos', function($id){

//when the post_id provided - return all post photos related to that user

	$post=Post::find($id);

	foreach($post->photos as $photo) {
		return $photo->path;
	}


});


Route::get('/photo/{id}/postOrUser',function($id){

	//return the user or post related to the photo. 

	$photo = Photo::findOrFail($id);

	return $photo->imageable;	

});




//---------polymorphic many to many


Route::get('/post/{id}/tag', function($id){
// 	 

	$post = Post::find($id);


	foreach($post->tags as $tag) {
		echo $tag->name;
	}
		
});


Route::get('/tag/{id}/post', function($id){


	$tag = Tag::find($id);

	foreach($tag->posts as $post){
		return $post->title;
	}
});

	


