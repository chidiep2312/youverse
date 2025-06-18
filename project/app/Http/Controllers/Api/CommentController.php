<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use App\Models\Thread;
use App\Models\User;
use App\Models\Post;
use App\Events\CommentPosted;

use App\Notifications\NewCommentNotification;

class CommentController extends Controller
{
    public function create(Request $request, $userId = null, $postId = null, $threadId = null)
    {
        $targetUser = null;
        $validator = Validator::make($request->all(), [
            'content' => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Wrong input!']);
        }

        if ($threadId) {
            $author = Thread::with('user')->where('id', $threadId)->first();
            $targetUser = $author->user;
        }
        if ($postId) {
            $author = Post::with('user')->find($postId);
            if ($author) {
                $targetUser = $author->user;
            }
        }

        $comment = new Comment();
        $comment->post_id = ($postId == 0) ? null : $postId;
        $comment->thread_id = $threadId;
        $comment->user_id = $userId;
        $comment->content = $request->input('content');
        $comment->save();
        if ($threadId) {
            broadcast(new CommentPosted($comment, $threadId));
        }
        if ($targetUser && $comment->user_id != $targetUser->id) {
            $targetUser->notify(new NewCommentNotification($comment, $targetUser));
        }

        return response()->json(['status_code' => 200, 'message' => 'Bình  luận thành công!']);
    }


    public function show(Thread $thread)
    {
        $comments = Comment::query()->where('thread_id', $thread->id)->get();
        return response()->json(['comment' => $comments, 'success' => true, 'message' => 'Bình luận thành công!']);
    }
    public function delete(User $userId, Request $request)
    {
        $comment = Comment::findOrFail($request->input('id'));

        $comment->delete();
        return response()->json(['status' => 'success']);
    }
}