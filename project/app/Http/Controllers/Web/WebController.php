<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;

class WebController extends Controller
{

    public function welcome()
    {
        $setting = Setting::first();
        $banner = [];
         if ($setting && $setting->banner) {
        $banner = json_decode($setting->banner, true); 
    }

        $famousPosts = Post::orderBy('view_count', 'desc')->limit(6)->get();
        $topUsers = User::select('users.id', 'users.name', 'users.avatar', DB::raw('COUNT(friendships.friend_id) as follower_count'))
            ->join('friendships', 'users.id', '=', 'friendships.friend_id')
            ->where('friendships.status', 'accepted')->orWhere('friendships.status', 'pendding')
            ->groupBy('users.id', 'users.name', 'users.avatar')
            ->orderByDesc('follower_count')
            ->limit(10)
            ->get();
        return view('welcome', compact('banner', 'famousPosts', 'topUsers'));
    }
    public function registerFrm()
    {
        return view('auth.register');
    }

    public function loginFrm()
    {
        return view('auth.login');
    }

   
   
}