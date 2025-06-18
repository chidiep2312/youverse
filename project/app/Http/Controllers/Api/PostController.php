<?php

namespace App\Http\Controllers\Api;

use App\Jobs\GeneratePostEmbedding;

use App\Models\Tag;

use App\Models\Post;
use App\Models\Like;
use App\Models\View;
use App\Models\Comment;
use App\Models\User;
use App\Models\Folder;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    //
    public function myPosts(Request $request)
    {
        $query = Post::query();
        $tags = Tag::all();
        $groups=Group::query()->where('user_id',Auth::id())->get();
        if ($request->has('tag_id') && $request->tag_id) {
            $query->where('tag_id', $request->tag_id);
        }
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
         if ($request->has('title') && $request->title) {
          $query->where('title','like','%' . $request->title . '%');
        }
           if ($request->has('group_id') && $request->group_id) {
          $query->where('group_id',$request->group_id);
        }
        $posts = $query->where('user_id',Auth::id())->latest()->paginate(10);
        return view('user.my-post-home', compact('posts','tags','groups'));
    }
   public function scheduleList(Request $request)
{
    $query = Post::query()->whereNotNull('scheduled_at');

    if ($request->has('status')) {
        if ($request->input('status') == 0) {
            $query->where('status', 'drafted');
        } elseif ($request->input('status') == 1) {
            $query->where('status', 'published');
        }
    }
    $posts = $query->where('user_id',Auth::id())->latest()->paginate(10);

    return view('user.schedule-list', compact('posts'));
}

    public function createPost()
    {
        $user = Auth::user();
        $tags = Tag::all();
        $folders = Folder::query()->where('user_id', $user->id)->get();
        return view('post.create', compact('tags', 'folders'));
    }
    public function createPostInGroup(User $user, Group $group)
    {
        $tags = Tag::all();
        return view('group.create-blog', compact('tags', 'user', 'group'));
    }

    public function save($userid, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'nullable|integer',
            'is_flag'=>'boolean|nullable',
            'des'=> 'nullable|string|max:255',
            'content' => 'required|string',
            'tag_id' => 'nullable|integer',
            'folder_id' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'in:drafted,published',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $post = new Post();
        if ($request->hasFile('image')) {
            $imgPath = $request->file('image')->store('images/post', 'public');
        } else {
            $imgPath = '';
        }

        $post->group_id = $request->group_id ?? null;
        $post->user_id = $userid;
        $post->title = $request->title;
        $post->des = $request->des;
        $post->tag_id = $request->tag_id;
        $post->folder_id = $request->folder_id;
        $post->content = $request->content;
        $post->status = $request->status ?? 'published';
        $post->scheduled_at = $request->scheduled_at;
        preg_match('/<img.*?src=["\'](.*?)["\']/', $post->content, $matches);
        $post->thumbnail = isset($matches[1]) ? $matches[1] : null;
        $post->save();
        GeneratePostEmbedding::dispatch($post->id);
        return response()->json(['id' => $post->id, 'success' => true, 'message' => 'Tạo bài viết mới thành công!']);
    }

    public function savePostImg(Request $request)
    {

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                return response()->json(['error' => 'Invalid image format!'], 400);
            }
            $path = $file->storeAs('uploads', $filename, 'public');

            if ($path) {
                return response()->json(['url' => asset('storage/' . $path)]);
            } else {
                return response()->json(['error' => 'Upload failed!'], 500);
            }
        }

        return response()->json(['error' => 'No file uploaded!'], 400);
    }


    public function detail(Post $post)
    {
        $user_id = Auth::user()->id;
        $author=$post->user_id;
        $comments = Comment::where('post_id', $post->id)->latest()->paginate(5);
        $view = View::query()
            ->where('user_id', $user_id)
            ->where('post_id', $post->id)
            ->first();
        if (!$view) {
            $post->increment('view_count');
            View::create([
                'user_id' => $user_id,
                'post_id' => $post->id,
            ]);
        }
       // $likesCount = Like::where('post_id', $post->id)->count();
        $relatedPosts=Post::query()->where('user_id',$author)->where('id', '!=', $post->id)
                    ->latest()
                    ->limit(6) 
                    ->get();
        return view('post.detail', compact('relatedPosts','post',  'comments'));
    }
    public function loadComments(Post $post)
    {
        $comments = Comment::where('post_id', $post->id)->latest()->paginate(3);
        return view('partials.comments', compact('comments'))->render();
    }


    public function likePost($id)
    {
        $userId = Auth::user()->id;
        $existingLike = Like::where('user_id', $userId)->where('post_id', $id)->first();
        if ($existingLike) {
            return response()->json(['message' => 'Bạn đã thích bài viết!']); 
        }

        Like::create([
            'user_id' => $userId,
            'post_id' => $id,
        ]);

        return response()->json(['success'=>true,'message' => 'Thích bài viết']); 
    }
    public  function edit(Post $post)
    {

        $tags = Tag::all();
        $folders = Folder::all();
        return view('post.edit', compact('post', 'tags', 'folders'));
    }
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'nullable|integer',
            'title' => 'string|required',
            'des' => 'string|max:255',
            'content' => 'required|string',
            'tag_id' => 'nullable|integer',
            'folder_id' => 'nullable|integer',
            'status' => 'in:drafted,published',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $post->group_id = $request->group_id ?? null;
        $post->title = $request->title;
        $post->des = $request->des;
        $post->tag_id = $request->tag_id;
        $post->folder_id = $request->folder_id;
        $post->content = $request->content;
        $post->status = $request->status ?? 'published';
        $post->scheduled_at = $request->scheduled_at;
        preg_match('/<img.*?src=["\'](.*?)["\']/', $post->content, $matches);
        $post->thumbnail = isset($matches[1]) ? $matches[1] : null;
        $post->save();
        GeneratePostEmbedding::dispatch($post->id);
        return response()->json([
            'id' => $post->id,
            'success' => true,
            'message' => 'Cập nhật thành công!'
        ]);
    }
    public function deletePost(Post $post)
    {

        $user_id = Auth::user()->id;
        if ($user_id != $post->user_id) {
            return response()->json(['success' => false, 'message' => 'Bạn không thể xóa bài này!'], 403);
        }
        $post->delete();

        return response()->json(['success' => true, 'message' => 'Xóa thành công!'], 200);
    }

    public function getTagPost($id, $user)
    {
        $posts = Post::where('tag_id', $id)->where('user_id', $user)->with('tag')->get();

        if ($posts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No posts found for the selected tag.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get posts successfully!',
            'posts' => $posts
        ]);
    }

    public function createComment(Request $request, $userId, $postId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Wrong input!']);
        }
        $comment = new Comment();
        $comment->post_id = $postId;
        $comment->user_id = $userId;
        $comment->content = $request->input('content');
        $comment->save();
        return response()->json(['success' => true, 'message' => 'Comment successfully!']);
    }

    public function draftedPost(Post $drafted)
    {
        return view('post.drafted', compact('drafted'));
    }
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications->where('id', $id)->first();
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }
}