<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Post;
use App\Models\Report;
use App\Models\Group;
use App\Models\Thread;
use App\Models\Tag;
use App\Models\Setting;
use App\Models\Announcement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function home()
    {
        $mostLikePosts = Post::query()->orderBy('like_count', 'desc')->limit(10)->get();
        $mostViewPosts = Post::query()->orderBy('view_count', 'desc')->limit(10)->get();
        $topReportedPosts = Report::with('post')
            ->select('post_id', DB::raw('COUNT(*) as report_count'))
            ->groupBy('post_id')
            ->orderByDesc('report_count')
            ->limit(5)
            ->get()
            ->map(function ($report) {
                return [
                    'title' => $report->post->title ?? 'Không tìm thấy',
                    'report_count' => $report->report_count,
                ];
            });
        $topReportedReasons = Report::query()
            ->select('reason', DB::raw('COUNT(*) as reason_count'))
            ->groupBy('reason')
            ->orderByDesc('reason_count')
            ->limit(5)
            ->get();


        return view('admin.dashboard', compact('mostLikePosts', 'mostViewPosts', 'topReportedPosts', 'topReportedReasons'));
    }

    public function personal()
    {
        $user = Auth::user();
        $annoucements = Announcement::query()->where('is_active', true)->orderBy('created_at', 'desc')->take(10);
        return view('admin.admin', compact('user', 'annoucements'));
    }


    public function statistic()
    {
        $users = User::count();
        $groups = Group::count();
        $threads = Thread::count();
        $active = Announcement::where('is_active', 1)->count();
        $announce = Announcement::count();
        $reports = Report::count();
        $pending = Report::where('status', 0)->count();
        $posts = Post::withFlagged()->count();
        $violationPosts = Post::withFlagged()->where('is_flag', true)->count();
        return response()->json(['success' => true, "data" => ['threads' => $threads, 'active' => $active, 'announce' => $announce, 'groups' => $groups, 'users' => $users, 'violationPosts ' => $violationPosts, 'reports' => $reports, 'pending' => $pending, 'posts' => $posts]]);
    }

    public function newUserByMonth()
    {
        $currentYear = now()->year;
        $monthlyUsers = DB::table('users')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();
        $datasets = [];
        $labels = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $item = $monthlyUsers->firstWhere('month', $m);
            $data[] = $item ? $item->total : 0;
        }
        $datasets[] = [
            'label' => 'Người dùng mới',
            'data' => $data,
            'borderColor' => '#007bff',
            'backgroundColor' => 'rgba(0,123,255,0.1)',
            'borderWidth' => 2,
            'tension' => 0.3
        ];

        return response()->json([
            'status' => 'success',
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }

    public function newPostByMonth()
    {
        $currentYear = now()->year;
        $monthlyPosts = DB::table('posts')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $flaggPosts = DB::table('posts')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->where('is_flag', true)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $labels = [
            'Tháng 1',
            'Tháng 2',
            'Tháng 3',
            'Tháng 4',
            'Tháng 5',
            'Tháng 6',
            'Tháng 7',
            'Tháng 8',
            'Tháng 9',
            'Tháng 10',
            'Tháng 11',
            'Tháng 12'
        ];

        $dataAllPosts = [];
        $dataFlaggedPosts = [];

        for ($m = 1; $m <= 12; $m++) {
            $item = $monthlyPosts->firstWhere('month', $m);
            $flag = $flaggPosts->firstWhere('month', $m);
            $dataAllPosts[] = $item ? $item->total : 0;
            $dataFlaggedPosts[] = $flag ? $flag->total : 0;
        }

        $datasets = [
            [
                'label' => 'Bài viết',
                'data' => $dataAllPosts,
                'borderColor' => '#28a745',
                'backgroundColor' => 'rgba(40,167,69,0.1)',
                'borderWidth' => 2,
                'tension' => 0.3
            ],
            [
                'label' => 'Bài viết vi phạm',
                'data' => $dataFlaggedPosts,
                'borderColor' => '#dc3545',
                'backgroundColor' => 'rgba(220,53,69,0.1)',
                'borderWidth' => 2,
                'tension' => 0.3
            ]
        ];

        return response()->json([
            'status' => 'success',
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }



    public function reportsByMonth()
    {
        $currentYear = now()->year;
        $monthlyReports = DB::table('reports')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();
        $datasets = [];
        $labels = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $item = $monthlyReports->firstWhere('month', $m);
            $data[] = $item ? $item->total : 0;
        }
        $datasets[] = [
            'label' => 'Bài viết bị báo cáo',
            'data' => $data,
            'borderColor' => 'rgb(207, 35, 72)',
            'backgroundColor' => 'rgba(233, 57, 57, 0.1)',
            'borderWidth' => 1

        ];

        return response()->json([
            'status' => 'success',
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }

    public function setting()
    {
        return view('admin.setting');
    }

    public function updateSetting(Request $request)
    {
        $request->validate([
            'banners.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'support_email' => 'email',
            'facebook' => 'string',
            'des' => 'string',
            'hotline' => 'string',

        ]);

        $setting = Setting::firstOrCreate();
        $currentBanners = json_decode($setting->banners ?? '[]');

        $newPaths = [];
        if ($request->file('banners')) {
            foreach ($request->file('banners') as $file) {
                $path = $file->store('banners', 'public');
                $newPaths[] = $path;
            }

            $allBanners = array_merge($currentBanners, $newPaths);
            $limitedBanners = array_slice($allBanners, 0, 3);
            $setting->banners = json_encode($limitedBanners);
        }
        $setting->support_email = $request->input('support_email');
        $setting->facebook = $request->input('facebook');
        $setting->hotline = $request->input('hotline');
        $setting->des = $request->input('des');
        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã upload thành công!',

        ]);
    }
   public function tagIndex(Request $request)
{
    $sort = $request->query('sort');

    $query = Tag::query();

    if ($sort === 'name') {
        $query->orderBy('tag_name');
    } elseif ($sort === 'oldest') {
        $query->orderBy('created_at'); 
    } else {
        $query->orderByDesc('created_at');
    }

    $tags = $query->paginate(10);

    return view('admin.tag.index', compact('tags', 'sort'));
}

    public function createTag(Request $request)
    {
        $request->validate([
            'tag_name' => 'required|string|max:255|unique:tags,tag_name',
        ]);
        $tag = new Tag();
        $tag->tag_name = $request->tag_name;
        $tag->save();
        return response()->json([
            'success' => true,
            'message' => 'Tạo mới thành công!',

        ]);
    }
    public function delete(Tag $tag)
    {
        if ($tag->post->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thẻ xóa thẻ đã có bài viết!',

            ]);
        }
        $tag->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công!',

        ]);
    }
    public function deleteAll(Request $request)
    {
        $ids = $request->input('ids', []);

        $deletedCount = Tag::whereIn('id', $ids)
            ->whereDoesntHave('post')
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Đã xóa {$deletedCount} thẻ không liên kết với bài viết.",
        ]);
    }
}