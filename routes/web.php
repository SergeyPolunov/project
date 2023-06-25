<?php

use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\CommentsController as AdminCommentsController;
use App\Http\Controllers\Admin\SubscribersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubsController;
use Illuminate\Support\Facades\Route;


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

Route::get('/', [HomeController::class, 'index']);
Route::get('/post/{slug}', [HomeController::class, 'show'])->name('post.show');
Route::get('/tag/{slug}', [HomeController::class, 'tag'])->name('tag.show');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::post('/subscribe', [SubsController::class, 'subscribe'])->name('subscribe');
Route::get('/verify/{token}', [SubsController::class, 'verify'])->name('subscribe.verify');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'store']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/comment', [CommentsController::class, 'store']);
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerForm']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::resources([
        '/' => DashboardController::class,
        'categories' => CategoriesController::class,
        'tags' => TagsController::class,
        'posts' => PostsController::class,
        'users' => UsersController::class,
        'comments' => AdminCommentsController::class,
        'subscribers' => SubscribersController::class,
    ]);
    Route::get('/comments/toggle/{comment}', [AdminCommentsController::class, 'toggle'])->name('comments.toggle');
    Route::delete('/comments/{id}/destroy', [AdminCommentsController::class, 'destroy']);
});
