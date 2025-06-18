<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Friendship;
use App\Models\Post;
use App\Models\Thread;
use App\Models\Like;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    //
    public function index(User $user)
    {
        $mostLike = Post::query()->where('user_id', $user->id)->orderBy('like_count', 'desc')->take(5)->get();
        $mostView = Post::query()->where('user_id', $user->id)->orderBy('view_count', 'desc')->take(5)->get();

        return view('user.statistical', compact('mostLike', 'mostView'));
    }

    public function statistic(User $user)
    {
        $posts = $user->posts()->count();
        $drafted = $user->posts()->where('status', 'drafted')->count();
        $views = $user->posts()->sum('view_count');
        $postIds = $user->posts()->pluck('id');

        $likes = Like::whereIn('post_id', $postIds)->count();

        $comments = $user->comments()->count();
        $followers = Friendship::query()->where('friend_id', $user->id)->where('status', '!=', 'declined')->count();
        $follows = Friendship::query()->where('user_id', $user->id)->where('status', '!=', 'declined')->count();
        $reports = $user->reports()->count();
        $groups = $user->groups()->withPivot('status', 'accepted')->count();
        return response()->json(['status' => "success", "data" => ['posts' => $posts, 'drafted' => $drafted, 'views' => $views, 'likes' => $likes, 'comments' => $comments, 'followers' => $followers, 'follows' => $follows, 'reports' => $reports, 'groups' => $groups]]);
    }

    public function linechart(User $user)
    {
        $currentYear = now()->year;
        $monthlyPosts = DB::table('posts')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('user_id', $user->id)
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();
        $datasets = [];
        $labels = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $item = $monthlyPosts->firstWhere('month', $m);
            $data[] = $item ? $item->total : 0;
        }
        $datasets[] = [
            'label' => 'Bài viết',
            'data' => $data,
            'borderColor' => '#50A625',
            'backgroundColor' => 'rgba(255,99,132,0.5)',
            'borderWidth' => 2,
            'tension' => 0.3
        ];

        return response()->json([
            'status' => 'success',
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }

    public function management(User $user)
    {
        $threads = Thread::query()->where('type', 'status')->where('user_id', $user->id)->paginate(10, ['*'], 'threads');
        $topics = Thread::query()->where('type', 'topic')->where('user_id', $user->id)->paginate(10, ['*'], 'topics');
        return view('user.management', compact('threads', 'topics'));
    }
}