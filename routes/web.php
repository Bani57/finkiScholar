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

use Illuminate\Support\Facades\Route;

Route::get('/csrf', function () {
    return json_encode(csrf_token());
});

Route::get('/users/check', 'UserController@checkIfUserLoggedIn');
Route::get('/users/current', 'UserController@getCurrentUser');
Route::post('/users/login', 'UserController@login');
Route::post('/users/logout', 'UserController@logout');
Route::post('/users/register', 'UserController@register');
Route::post('/users/upload', 'UserController@uploadProfilePicture');
Route::delete('/users/{id}/delete', 'UserController@deleteAccount');
Route::get('/users/{id}/role', 'UserController@getRole');
Route::get('/users/{id}/author-info', 'UserController@getAuthorInfo');

Route::get('/authors', 'AuthorController@getAllAuthors');
Route::get('/authors/{id}/papers', 'AuthorController@getPapersByAuthor');
Route::get('/authors/{id}/topics', 'AuthorController@getTopicsForPapers');
Route::get('/authors/{id}/citations', 'AuthorController@getNumCitationsForPapers');
Route::get('/authors/{id}/authors', 'AuthorController@getCoAuthorsForPapers');
Route::get('/authors/{id}/reviews', 'AuthorController@getReviewsForPapers');
Route::get('/authors/{id}/reviewers', 'AuthorController@getReviewersForPapers');

Route::get('/papers', 'PaperController@getAllPapers');
Route::get('/papers/accepted', 'PaperController@getAcceptedPapers');
Route::post('/papers', 'PaperController@publishNewPaper');
Route::post('/papers/upload', 'PaperController@uploadPdfFile');
Route::patch('/papers/update', 'PaperController@updateStatusForPapers');

Route::get('/reviewers/{id}/reviews', 'ReviewerController@getPendingReviews');
Route::get('/reviewers/{id}/authors', 'ReviewerController@getLeadAuthorsForPendingReviews');

Route::put('/reviews', 'ReviewController@updateReview');

Route::get('/topics', 'TopicController@getAllTopics');
Route::get('/topics/roots', 'TopicController@getRootTopics');
Route::get('/topics/{id}/children', 'TopicController@getSubTopics');
Route::get('/topics/{id}/ancestors', 'TopicController@getAncestors');
Route::get('/topics/{id}/papers', 'TopicController@getPapers');
Route::get('/topics/{id}/authors', 'TopicController@getAuthorsForPapers');


Route::get('/{any}', function () {
    return view('vueapp');
})->where('any', '()|(.*-page)|(profile)|(archive)');
