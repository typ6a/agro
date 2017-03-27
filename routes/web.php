<?php
Route::get('bellson', 'BellsonMainController@getBellsonCategories');

Route::get('/', 'PagesController@home');

Route::get('home', 'PagesController@home');


Route::get('/about', 'PagesController@about');


Route::get('/contact', 'TicketsController@create');
Route::post('/contact', 'TicketsController@store');


Route::get('/tickets', 'TicketsController@index');

Route::get('/ticket/{slug?}', 'TicketsController@show');

Route::get('/ticket/{slug?}/edit','TicketsController@edit');
Route::post('/ticket/{slug?}/edit','TicketsController@update');

Route::post('/ticket/{slug?}/delete','TicketsController@destroy');

Route::post('/comment', 'CommentsController@newComment');


Route::get('users/register', 'Auth\RegisterController@showRegistrationForm');
Route::post('users/register', 'Auth\RegisterController@register');

Route::get('users/logout', 'Auth\LoginController@logout');

Route::get('users/login', 'Auth\LoginController@showLoginForm');
Route::post('users/login', 'Auth\LoginController@login');

Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'manager'), function () {
    
    Route::get('/', 'PagesController@home');

    Route::get('users', [ 'as' => 'admin.user.index', 'uses' => 'UsersController@index']);
    Route::get('roles', 'RolesController@index');
    Route::get('roles/create', 'RolesController@create');
    Route::post('roles/create', 'RolesController@store');
    Route::get('users/{id?}/edit', 'UsersController@edit');
    Route::post('users/{id?}/edit','UsersController@update');


    Route::get('posts', 'PostsController@index');
    Route::get('posts/create', 'PostsController@create');
    Route::post('posts/create', 'PostsController@store');
    Route::get('posts/{id?}/edit', 'PostsController@edit');
    Route::post('posts/{id?}/edit','PostsController@update');


    Route::get('categories', 'CategoriesController@index');
    Route::get('categories/create', 'CategoriesController@create');
    Route::post('categories/create', 'CategoriesController@store');

});




Route::group(array('prefix' => 'trader', 'namespace' => 'Trader', 'middleware' => 'trader'), function () {
    
    Route::get('/', 'PagesController@home');

    Route::get('ads', 'AdsController@index');
    Route::get('ads/create', 'AdsController@create');
    Route::post('ads/create', 'AdsController@store');
    Route::get('ads/{id?}/edit', 'AdsController@edit');
    Route::post('ads/{id?}/edit','AdsController@update');

    Route::get('categories', 'CategoriesController@index');
    Route::get('categories/create', 'CategoriesController@create');
    Route::post('categories/create', 'CategoriesController@store');
});

Route::post('ads/','AdsController@index');//?????




Route::get('sendemail', function () {

    $data = array(
        'name' => "Learning Laravel",
    );

    Mail::send('emails.welcome', $data, function ($message) {

        $message->from('newsuperznak@gmail.com', 'Learning Laravel');

        $message->to('znakd@mail.ru')->subject('agro portal test email 1');

    });

    return "Your email has been sent successfully";

});


Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/blog', 'BlogController@index');
Route::get('/blog/{slug?}', 'BlogController@show');








