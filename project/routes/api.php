<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\ThreadController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\Admin\AdminGroupController;
use App\Http\Controllers\Api\Admin\AdminPostController;
use App\Http\Controllers\Api\Admin\AdminThreadController;
use App\Http\Controllers\Api\Admin\AdminAnnouncementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/like-post/{id}', [PostController::class, 'likePost'])->name('like');
});
//personal
Route::group(['prefix' => 'personal', 'as' => 'personal.'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/update-name/{userId}', [UserController::class, 'updateName'])->name('updateName');
        Route::post('/update-avatar/{user}', [UserController::class, 'updateAvatar'])->name('updateAvatar');
        Route::post('/slogan/{user}', [UserController::class, 'changeSlogan'])->name('slogan');
        Route::post('/update-bgr/{user}', [UserController::class, 'updateBgr'])->name('updateBgr');
        Route::post('/unblock/{friend_id}', [UserController::class, 'unblock'])->name('unblock');
        Route::post('/block/{friend_id}', [UserController::class, 'block'])->name('block');
        Route::post('/unfriend/{friend_id}', [UserController::class, 'unfriend'])->name('unfriend');
    });
});
//group
Route::group(['prefix' => 'group', 'as' => 'group.'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/leave/{user}/{group}', [GroupController::class, 'leave'])->name('leave');
        Route::post('/delete/{group}', [GroupController::class, 'delete'])->name('delete');
        Route::post('/create-group/{userId}', [GroupController::class, 'save'])->name('save');
        Route::post('/update/{group}', [GroupController::class, 'updateGroup'])->name('update-group');
        Route::post('/find', [GroupController::class, 'find'])->name('find');
        Route::post('/find-result/join/{user}/{group}', [GroupController::class, 'join'])->name('join');
        Route::post('/approve/{group}', [GroupController::class, 'approve'])->name('approve');
        Route::post('/reject/{group}', [GroupController::class, 'reject'])->name('reject');
        Route::post('/remove/{group}', [GroupController::class, 'remove'])->name('remove');
        Route::post('/invite/{user}', [GroupController::class, 'invite'])->name('invite');
        Route::post('/invite', [GroupController::class, 'inviteUser'])->name('invite-user');
    });
});
Route::group(['prefix' => 'folder', 'as' => 'folder.'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/delete/{folder}', [FolderController::class, 'delete'])->name('delete');
        Route::post('/create-folder/{userId}', [FolderController::class, 'save'])->name('save');
    });
});
//thread
Route::group(['prefix' => 'thread', 'as' => 'thread.'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/save/{userId}', [ThreadController::class, 'save'])->name('save');
        Route::post('/comment/{userId}/{postId?}/{threadId?}', [CommentController::class, 'create'])->name('comment');
        Route::delete('/delete-thread/{thread}', [ThreadController::class, 'delete'])->name('delete');
        Route::post('/delete-comment/{userId}', [CommentController::class, 'delete']);
        Route::post('/save-topic', [ThreadController::class, 'saveTopic'])->name('save-topic');
    });
});
//report
Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
    Route::post('/post/{userId}', [ReportController::class, 'reportPost'])->name('report')->middleware('auth:sanctum');
});
//post
Route::group(['prefix' => 'post', 'as' => 'post.'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/create-comment/{userId}/{postId}', [CommentController::class, 'create'])->name('comment');
        Route::post('/comment/delete/{userId}', [CommentController::class, 'delete'])->name('commentDelete');
        Route::post('/get-tag-posts/{id}', [PostController::class, 'getTagPost'])->name('tag');
        Route::post('/upload-image', [PostController::class, 'savePostImg'])->name('savePostImg');
        Route::post('/save-post/{userId}', [PostController::class, 'save'])->name('save');
        Route::post('/update-post/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/delete-post/{post}', [PostController::class, 'deletePost'])->name('delete');
    });
});
//notification
Route::group(['prefix' => 'nofication', 'as' => 'nofication.'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/follow-user', [UserController::class, 'follow'])->name('user');
        Route::post('/follow-back/{friendship}', [HomeController::class, 'followBack'])->name('back');
    });
});

//admin
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::middleware(['checkRole:admin', 'auth:sanctum'])->group(function () {
        Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
            Route::post('/update', [AdminController::class, 'updateSetting'])->name('update');
        });
        Route::group(['prefix' => 'group', 'as' => 'group.'], function () {
            Route::post('/inactive-multi', [AdminGroupController::class, 'inactiveMulti'])->name('inactive-multi');
        });
        Route::group(['prefix' => 'annoucement', 'as' => 'annoucement.'], function () {
            Route::post('/save', [AdminAnnouncementController::class, 'createAnnouncement'])->name('create');
            Route::get('/inactive/{ann}', [AdminAnnouncementController::class, 'inActiveAnn'])->name('block');
            Route::get('/active/{ann}', [AdminAnnouncementController::class, 'activeAnn'])->name('open');
            Route::post('/delete/{ann}', [AdminAnnouncementController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'tag', 'as' => 'tag.'], function () {
          
            Route::post('/save', [AdminController::class, 'createTag'])->name('save');
            Route::post('/delete/{tag}', [AdminController::class, 'delete'])->name('delete');
             Route::post('/delete-all', [AdminController::class, 'deleteAll'])->name('delete-all');
        });
        Route::group(['prefix' => 'post', 'as' => 'post.'], function () {
            Route::post('/delete/{post}', [AdminPostController::class, 'deletePost'])->name('deletePost');
            Route::post('/delete-multi', [AdminPostController::class, 'deleteMultiPost'])->name('deleteMultiPost');
            Route::post('/delete-report/{report}', [AdminPostController::class, 'deleteReportPost'])->name('delete');
            Route::post('/done-report/{report}', [AdminPostController::class, 'doneReportPost'])->name('done');
            Route::post('/approve-post/{id}', [AdminPostController::class, 'approvePost'])->name('approve');
        });

        Route::group(['prefix' => 'thread', 'as' => 'thread.'], function () {
            Route::delete('/delete-thread/{thread}', [AdminThreadController::class, 'delete'])->name('delete');
        });
    });
});