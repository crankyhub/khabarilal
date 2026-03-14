<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ReporterManagementController;
use App\Http\Controllers\Admin\AdController;

use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\HealthController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Public\HomeController as PublicHomeController;
use App\Http\Controllers\Public\ArticleController as PublicArticleController;

Route::get('/', [PublicHomeController::class, 'index']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::post('/push/subscribe', [\App\Http\Controllers\PushController::class, 'subscribe'])->name('push.subscribe');
Route::post('/push/unsubscribe', [\App\Http\Controllers\PushController::class, 'unsubscribe'])->name('push.unsubscribe');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('articles', ArticleController::class);
    Route::patch('/articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve');
    Route::patch('/articles/{article}/reject', [ArticleController::class, 'reject'])->name('articles.reject');

    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class);
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/send', [\App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('notifications.send');
    Route::resource('reporters', ReporterManagementController::class);
    Route::post('/reporters/{user}/toggle-status', [ReporterManagementController::class, 'toggleStatus'])->name('reporters.toggle-status');

    Route::resource('ads', AdController::class);
    Route::resource('media', MediaController::class);
    Route::get('/api/media', [MediaController::class, 'apiIndex'])->name('api.media.index');
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::patch('/comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
    Route::delete('/newsletter/{id}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');
    Route::get('/health', [HealthController::class, 'index'])->name('health.index');
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::post('/impersonate/{user}', [ImpersonationController::class, 'impersonate'])->name('impersonate');
    Route::post('/stop-impersonating', [ImpersonationController::class, 'stop'])->name('stop-impersonating');

    // Profile Management
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::get('/article/{slug}', [PublicArticleController::class, 'show'])->name('article.show');
Route::get('/category/{slug}', [PublicHomeController::class, 'category'])->name('category.show');
Route::get('/tag/{slug}', [PublicHomeController::class, 'tag'])->name('tag.show');
Route::get('/search', [\App\Http\Controllers\Public\SearchController::class, 'index'])->name('search');
Route::get('/reporter/{id}', [\App\Http\Controllers\Public\ReporterController::class, 'show'])->name('reporter.show');
Route::get('/sitemap.xml', [\App\Http\Controllers\Public\SitemapController::class, 'index'])->name('sitemap');
Route::get('/swipe', [PublicHomeController::class, 'swipe'])->name('swipe.index');
Route::post('/newsletter/subscribe', [\App\Http\Controllers\Public\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/article/{article}/comments', [\App\Http\Controllers\Public\CommentController::class, 'store'])->name('comments.store');
Route::get('/ad/click/{ad}', [\App\Http\Controllers\Public\AdController::class, 'click'])->name('ads.click');
Route::post('/article/{article}/rate', [\App\Http\Controllers\Public\RatingController::class, 'store'])->name('article.rate');
