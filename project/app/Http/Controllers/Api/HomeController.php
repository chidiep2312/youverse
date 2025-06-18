<?php

namespace App\Http\Controllers\Api;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Thread;
use App\Models\Friendship;
use App\Models\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Notifications\FollowBackNotification;

class HomeController extends Controller
{
  public function setPass()
  {
    return view('auth.set-pass');
  }

  public function loginFail()
  {
    return view('auth.fail');
  }
  private function cosineSimilarity(Collection $a, Collection $b)
  {
    $dot = $a->zip($b)->reduce(fn($carry, $pair) => $carry + $pair[0] * $pair[1], 0);
    $magA = sqrt($a->reduce(fn($carry, $x) => $carry + $x ** 2, 0));
    $magB = sqrt($b->reduce(fn($carry, $x) => $carry + $x ** 2, 0));
    if ($magA == 0 || $magB == 0) {
      return 0;
    }
    return $dot / ($magA * $magB);
  }
  private function similarPosts(User $user)
  {
    if (!$user) abort(403);

    $viewedPostIds = View::where('user_id', $user->id)->pluck('post_id');

    $viewedEmbeddings = Post::whereIn('id', $viewedPostIds)
      ->whereNotNull('embedding')
      ->get()
      ->map(function ($post) {
        return collect(json_decode($post->embedding, true))->map(fn($x) => (float) $x);
      });

    $avgEmbedding = collect([]);
    if ($viewedEmbeddings->count() > 0) {
      $avgEmbedding = $viewedEmbeddings->reduce(function ($carry, $embed) {
        if ($carry->isEmpty()) {
          return $embed;
        }
        return $carry->zip($embed)->map(fn($pair) => $pair[0] + $pair[1]);
      }, collect())->map(fn($x) => $x / $viewedEmbeddings->count());
    }

 
    $otherPosts = Post::whereNotIn('id', $viewedPostIds)
      ->whereNull('group_id')
      ->whereNotNull('embedding')
      ->get();

    $sorted = $otherPosts->map(function ($post) use ($avgEmbedding) {
      $embedding = collect(json_decode($post->embedding, true))->map(fn($x) => (float) $x);
      $similarity = $this->cosineSimilarity($avgEmbedding, $embedding);
      $post->similarity = $similarity;
      return $post;
    })->sortByDesc('similarity')->values();

   
    $page = request()->get('page', 1);
    $perPage = 10;
    $paginated = new LengthAwarePaginator(
      $sorted->slice(($page - 1) * $perPage, $perPage)->values(),
      $sorted->count(),
      $perPage,
      $page,
      ['path' => request()->url(), 'query' => request()->query()]
    );

    return $paginated;
  }


  public function home()
  {
    $user = Auth::user();
    $tags = Tag::withCount('posts')
      ->orderByRaw("
        CASE 
            WHEN posts_count = 0 THEN 1 
            ELSE 0 
        END,
        posts_count DESC,
        created_at DESC
    ")
      ->limit(10)
      ->get();

    $announcements = Announcement::query()->where('is_active', true)->orderBy('created_at', 'desc')->get();
    $mostLikes = Post::query()->orderBy('like_count', 'desc')->whereNull('group_id')->paginate(3, ['*'], 'mostViews');
    $mostViews = Post::query()->orderBy('view_count', 'desc')->whereNull('group_id')->paginate(3, ['*'], 'mostLikes');
    $newest_posts = Post::query()->orderBy('created_at', 'desc')->whereNull('group_id')->paginate(5, ['*'], 'newest_posts');
    $threads = Thread::where('type', 'status')->latest()->paginate(10);
    $suggested = $this->similarPosts($user);
    return view('home', compact('tags', 'mostLikes', 'mostViews', 'newest_posts', 'threads', 'announcements', 'suggested'));
  }
  public function tagResult($id)
  {
    $tags = Tag::withCount('posts')
      ->orderByDesc('posts_count')
      ->limit(12);
    $posts = Post::query()->where('tag_id', $id)->latest()->paginate(10);
    return view('post.tag', compact('posts', 'tags'));
  }

  public function friendList($id)
  {

    $friends = Friendship::where(function ($query) use ($id) {
      $query->where('user_id', $id)->where('status', 'accepted')->orWhere(function ($subquery) use ($id) {
        $subquery->where('friend_id', $id)->where('status', 'accepted');
      });
    })->with('user', 'friend')->get();
    return view('friends.list', compact('friends', 'id'));
  }

  public function followBack(FriendShip $friendship, Request $request)
  {
    $friendship->status = 'accepted';
    $friendship->save();
    $receiver = $friendship->user;
    $notification = Auth::user()->notifications->where('id', $request->input('id'))->first();
    $notification->markAsRead();

    $receiver->notify(new  FollowBackNotification($friendship));
    return response()->json(['success' => true, 'message' => 'Đồng ý kết bạn!']);
  }

  public function homeLoad(User $user)
  {
    return response()->json(['success' => true, 'user' => $user]);
  }
  public function search(Request $request)
  {
    $searchInput = $request->input('search');


    $user = User::where('id', $searchInput)
      ->orWhere('email', $searchInput)
      ->first();
    if ($user && $user->id !== Auth::id())  {
      return response()->json([
        'success' => true,
        'type' => 'user',
        'user' => $user,
        'id' => $user->id,
      ]);
    }
    $posts = Post::where(function ($query) use ($searchInput) {
      $query->where('title', 'like', '%' . $searchInput . '%')
        ->orWhere('des', 'like', '%' . $searchInput . '%');
    })->whereNull('group_id')->latest()->paginate(10);

    return response()->json([
      'success' => true,
      'type' => 'posts',
      'posts' => $posts,
    ]);
  }
  public function searchResult(Request $request)
  {
    $searchInput = $request->query('query');

    $posts = Post::where('title', 'like', '%' . $searchInput . '%')
      ->orWhere('des', 'like', '%' . $searchInput . '%')
      ->whereNull('group_id')
      ->latest()
      ->paginate(10);

    return view('post.search-post-result', compact('posts'));
  }
  public function forum(Request $request)
  {
    $tags = Tag::all();
    $query = Thread::query()->where("type", "topic");
    if ($request->has('title') && $request->input('title')) {
      $query->where('title', 'like', '%' . $request->title . '%');
    }
    if ($request->has('tag_id') && $request->input('tag_id')) {

      $query->where('tag_id', $request->tag_id);
    }
    if ($request->has('status')) {
      if ($request->input('status') == "latest") {
        $query->latest();
      }
      if ($request->input('status') == "popular") {
        $query->withCount('comments')->orderByDesc('comments_count');
      }
    }
    $topics = $query->paginate(5);
    $pinned = Thread::query()->where('type', 'topic')->where('is_pinned', true)->limit(6)->get();
    return view('user.forum', compact('topics', 'tags', 'pinned'));
  }
  public function markAsRead($id)
    {
        $notification = Auth::user()->notifications->where('id', $id)->first();
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }
}