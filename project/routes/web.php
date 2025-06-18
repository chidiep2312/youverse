<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ThreadController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\Admin\AdminGroupController;
use App\Http\Controllers\Api\Admin\AdminPostController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminAnnouncementController;
use App\Http\Controllers\Api\Admin\AdminThreadController;

Route::get('/', [WebController::class, 'welcome'])->name('welcome');

// Đăng ký và đăng nhập
Route::get('/register', [WebController::class, 'registerFrm'])->name('register');
Route::get('/login', [WebController::class, 'loginFrm'])->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/set-password', [HomeController::class, 'setPass'])->name('setPass');


Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/google-success', [AuthController::class, 'googleSuccess'])->name('google.success');

Route::get('/2fa/verify', [AuthController::class, 'showForm'])->name('2fa.verify');
Route::post('/2fa/verify-login', [AuthController::class, 'verify']);

// // Trang chính 
Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/forum', [HomeController::class, 'forum'])->name('forum');
Route::get('/login-fail', [HomeController::class, 'loginFail'])->name('login-fail');
//trang nguoi ban theo doi
Route::get('/friend/{userId}', [HomeController::class, 'friendList'])->name('friend');
//man hinh gui tin nhan
Route::get('/chat/{sender}/{receiver}', [MessageController::class, 'chat'])->name('chat');
Route::post('/send-message/{sender}/{receiver}', [MessageController::class, 'sendMess'])->name('sendMess')->middleware('auth');
Route::get('/mark-as-read-post/{id}', [PostController::class, 'markAsRead']);
Route::get('/mark-as-read/{id}', [MessageController::class, 'markAsRead'])->name('sendMess')->middleware('auth');
Route::get('/mark-as-read-thread/{id}/{threadId}', [ThreadController::class, 'markAsRead']);
Route::get('/tag-result/{id}', [HomeController::class, 'tagResult'])->name('tag');
Route::post('/search', [HomeController::class, 'search'])->name('search')->middleware('auth:sanctum');;
Route::get('/follow-back/mark-as-read/{id}', [HomeController::class, 'markAsRead'])->name('followback')->middleware('auth');

//personal
Route::group(['middleware' => ['checkRole:user']], function () {
    Route::group(['prefix' => 'personal', 'as' => 'personal.'], function () {
        Route::get('/load-bgr/{user}', [HomeController::class, 'homeLoadBgr'])->name('homeLoadBgr');
        Route::get('/load-avatar/{user}', [HomeController::class, 'homeLoad'])->name('homeLoad');
        Route::get('/personal-page/{user}', [UserController::class, 'show'])->name('personal');
        Route::get('/getavar', [UserController::class, 'getAvatar'])->name('Ava');
        Route::get('/personal-tag-post/{id}/{user}', [PostController::class, 'getTagPost'])->name('tagPost');
    });

    Route::group(['prefix' => 'group', 'as' => 'group.'], function () {
        Route::get('/{userId}', [GroupController::class, 'index'])->name('index');
        Route::get('/manage/{group}', [GroupController::class, 'manage'])->name('manage')->middleware('auth');
        Route::get('/detail/{user}/{group}', [GroupController::class, 'detail'])->name('detail');
        Route::get('/blog/{user}/{group}', [PostController::class, 'createPostInGroup'])->name('createPost');
        Route::get('/find-result/{group}', [GroupController::class, 'result'])->name('result');
        Route::get('/accept-invite/{group}', [GroupController::class, 'acceptInvite'])->name('accept-invite');
        Route::get('/mark-as-read/{id}/{group}', [GroupController::class, 'markAsRead']);
        Route::get('/mark-as-read-other/{id}', [GroupController::class, 'markAsReadSimple']);
    });

    Route::group(['prefix' => 'thread', 'as' => 'thread.'], function () {
        Route::get('/detail/{thread}', [ThreadController::class, 'detail'])->name('detail');
        Route::get('/topic/create', [ThreadController::class, 'createTopic'])->name('create-topic');
        Route::get('/topic/detail/{topic}', [ThreadController::class, 'detailTopic'])->name('detail-topic');
    });

    //folder
    Route::group(['prefix' => 'folder', 'as' => 'folder.'], function () {
        Route::get('/{userId}', [FolderController::class, 'index'])->name('index');
        Route::get('/user-view/{folder}/{userId}', [FolderController::class, 'userView'])->name('user-view');
        Route::get('/detail/{user}/{folder}', [FolderController::class, 'detail'])->name('detail');
    });
    //post
    Route::group(['prefix' => 'post', 'as' => 'post.'], function () {
        Route::group(['middleware' => ['web']], function () {
            Route::get('/my-posts', [PostController::class, 'myPosts'])->name('my-posts');
            Route::get('/schedule', [PostController::class, 'scheduleList'])->name('schedule');
            Route::get('/create-post', [PostController::class, 'createPost'])->name('create');
            Route::get('/drafted-post/{drafted}', [PostController::class, 'draftedPost'])->name('drafted');
            Route::get('/detail-post/{post}', [PostController::class, 'detail'])->name('detail');
            Route::get('/edit-post/{post}', [PostController::class, 'edit'])->name('edit-post');
            Route::get('/{post}/comments', [PostController::class, 'loadComments'])->name('post.loadComments');
            Route::get('/search-result', [HomeController::class, 'searchResult'])->name('search-result');

            Route::post('/save-img', [PostController::class, 'savePostImg'])->name('saveImg');
        });
    });

    //nofication
    Route::group(['prefix' => 'nofication', 'as' => 'nofication.'], function () {
        Route::group(['middleware' => ['web']], function () {
            Route::get('/follow/{id}', [HomeController::class, 'followNotification'])->name('follow');
        });
    });

    //friend
    Route::group(['prefix' => 'friend', 'as' => 'friend.'], function () {
        Route::group(['middleware' => ['web']], function () {
            Route::get('/search/{user}', [UserController::class, 'searchUser'])->name('search');
            Route::get('/page/{user}', [UserController::class, 'showFriendPage'])->name('page');
        });
    });
    //statistic
    Route::group(['prefix' => 'statistic', 'as' => 'statistic.'], function () {
        Route::group(['middleware' => ['web']], function () {
            Route::get('/statistic/{user}', [StatisticController::class, 'statistic'])->name('data');
            Route::get('/linechart/{user}', [StatisticController::class, 'linechart'])->name('linechart');
            Route::get('/{user}', [StatisticController::class, 'index'])->name('index');
        });
    });

    Route::group(['prefix' => 'management', 'as' => 'management.'], function () {
        Route::group(['middleware' => ['web']], function () {
            Route::get('/{user}', [StatisticController::class, 'management'])->name('data');
        });
    });
});

//forgot password
Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
    Route::group(['middleware' => ['web']], function () {
        Route::get('/reset-password', [AuthController::class, 'forgotFrm'])->name('forgotFrm');
        Route::get('/set-password/{id}', [AuthController::class, 'setPwdFrm'])->name('setPwdFrm');
        Route::post('/reset-password', [AuthController::class, 'postForgotFrm'])->name('postForgotFrm');
        Route::post('/change-password/{id}', [AuthController::class, 'changePass']);

        Route::post('/set-password/{user}', [AuthController::class, 'postSetPwdFrm'])->name('postSetPwdFrm');
    });
});
//admin
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => ['web', 'checkRole:admin']], function () {
        Route::get('/', [AdminController::class, 'home'])->name('dashboard');

        Route::get('/personal', [AdminController::class, 'personal'])->name('personal')->middleware('auth');
        Route::get('/statistic', [AdminController::class, 'statistic'])->name('data');
        Route::get('/chart/new-users', [AdminController::class, 'newUserByMonth'])->name('new-users');
        Route::get('/chart/new-posts', [AdminController::class, 'newPostByMonth'])->name('new-posts');
        Route::get('/chart/reports', [AdminController::class, 'reportsByMonth'])->name('reports');


        Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
            Route::get('/', [AdminController::class, 'setting'])->name('index')->middleware('checkRole:admin');
        });

        Route::group(['prefix' => 'annoucement', 'as' => 'annoucement.'], function () {
            Route::get('/', [AdminAnnouncementController::class, 'index'])->name('index');
        });

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {

            Route::get('/list', [AdminUserController::class, 'listUser'])->name('list');
            Route::get('/detail/{user}', [AdminUserController::class, 'detail'])->name('detail');
            Route::get('/warning/{user}', [AdminUserController::class, 'sendWarningMail'])->name('sendMail');
            Route::get('/block/{user}', [AdminUserController::class, 'blockUser'])->name('block');
            Route::get('/unblock/{user}', [AdminUserController::class, 'unblockUser'])->name('unblock');
        });

        Route::group(['prefix' => 'group', 'as' => 'group.'], function () {

            Route::get('/list', [AdminGroupController::class, 'groupList'])->name('list');
            Route::get('/inactive/{group}', [AdminGroupController::class, 'inactiveGroup'])->name('inactive');
            Route::get('/active/{group}', [AdminGroupController::class, 'activeGroup'])->name('active');
            Route::get('/detail/{group}', [AdminGroupController::class, 'detailGroup'])->name('detail');
        });
        Route::group(['prefix' => 'post', 'as' => 'post.'], function () {
            Route::get('/list', [AdminPostController::class, 'listPost'])->name('list');
            Route::get('/list-violation', [AdminPostController::class, 'listViolation'])->name('list-violation');
            Route::get('/list-post', [AdminPostController::class, 'list'])->name('list-post');
            Route::get('/detail-report/{report}', [AdminPostController::class, 'detailReport'])->name('detail-report');
            Route::get('/detail-post/{post}', [AdminPostController::class, 'detailPost'])->name('detail-post');
            Route::get('/detail-flagged-post/{id}', [AdminPostController::class, 'detailFlaggedPost'])->name('detail-flagged-post');
        });

        Route::group(['prefix' => 'tag', 'as' => 'tag.'], function () {
            Route::get('/index', [AdminController::class, 'tagIndex'])->name('index');
        });
        Route::group(['prefix' => 'thread', 'as' => 'thread.'], function () {
            Route::get('/', [AdminThreadController::class, 'list'])->name('list');
            Route::get('/topic/pin/{topic}', [AdminThreadController::class, 'pin'])->name('pin');
            Route::get('/topic/unpin/{topic}', [AdminThreadController::class, 'unpin'])->name('unpin');
            Route::get('/detail-topic/{topic}', [AdminThreadController::class, 'detailTopic'])->name('detail-topic')->middleware('auth');;
        });
    });
});
