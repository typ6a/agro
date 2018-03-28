<?php
Route::get('prolum', 'ProlumController@getProlum');
Route::get('nums', 'NumsController@getMaxPrimesMultiplicationPalindrome');
Route::get('bellson', 'BellsonMainController@getBellsonCategories');
Route::get('kupalniki', 'KupalnikiMainController@getKupalnikiCategories');
Route::get('promuacategories', 'PromUaMainController@getPromUaCategories');
Route::get('paton', 'PatonMainController@getPaton');
Route::get('ukrapk', 'UkrapkMainController@getUkrapk');
Route::get('kenga1', 'kenga1MainController@getKenga1Categories');
Route::get('kenga2', 'kenga2MainController@getKenga2Categories');
Route::get('kenga3', 'kenga3MainController@getKenga3Categories');
Route::get('kenga4', 'kenga4MainController@getKenga4Categories');
Route::get('kenga5', 'kenga5MainController@getKenga5Categories');
Route::get('kenga6', 'kenga6MainController@getKenga6Categories');
Route::get('kenga7', 'kenga7MainController@getKenga7Categories');
Route::get('kenga8', 'kenga8MainController@getKenga8Categories');
Route::get('tripolifermers', 'TripoliMainController@getTripoliFermers');
Route::get('sendTripoliFermers', 'TripoliMainController@sendEmailsTripoliFermers');
Route::get('horozua', 'HorozMainController@getHorozua');

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


// Route::get('sendemailfermers', function () {
//     $data = array(
//         'name' => "NewSuperLed",
//     );
//     $filename ='../storage/tripoli/tripoliFermers1.csv';
//     $attachFilename ='../storage/tripoli/КП_Улица_Пром.pdf';
//     $lines = file($filename, FILE_IGNORE_NEW_LINES);
//     $myMails = [
//         'znakd@ukr.net',
//         'newsuperznak@gmail.com',
//         'znakverona@gmail.com',
//         'newsuperagro@gmail.com'

//         ];
//     foreach ($myMails as $myMail) {
//         pre($myMail);
//         Mail::send('emails.tripoliFermers', $data, function ($message) {

//         $message->from('newsuperznak@gmail.com', 'Светодиодное освещение.');

//         $message->to($this->myMail)->subject('Светодиодное освещение. Предложение.');
//         $message->attach($attachFilename);
//         pre($myMail . 'отослано!');

//         });
//     }
// });

Route::get('sendemail', function () {
    $data = array(
        'name' => "Learning Laravel",
    );
    Mail::send('emails.welcome', $data, function ($message) {
        $message->from('newsuperznak@gmail.com', 'Learning Laravel');
        $message->to('newsuperznak@gmail.com')->subject('agro portal test email 1');
        // $message->attach($pathToFile);
    });
    return "Your email has been sent successfully";
});


Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/blog', 'BlogController@index');
Route::get('/blog/{slug?}', 'BlogController@show');








