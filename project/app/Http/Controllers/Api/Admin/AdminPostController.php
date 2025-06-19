<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Post;
use App\Models\Report;
use App\Models\Like;
use App\Models\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminDeletePostMail;
use App\Mail\DeletePostMail;
use App\Mail\ApproveViolationPostMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminPostController extends Controller
{
    //
    public function list(Request $request)
    {

        $query = Post::with(['user', 'tag'])->whereDoesntHave('reports');

        if ($request->has('title') && $request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->has('user') && $request->user) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        if ($request->has('tag_id') && $request->tag_id) {
            $query->where('tag_id', $request->tag_id);
        }

        $posts = $query->where('status', 'published')->latest()->paginate(10);

        $tags = Tag::all();


        return view('admin.posts.all', compact('posts', 'tags'));
    }
    public function listPost()
    {
        $reports = Report::with(['post.user', 'user'])->where('status', 0)->orderBy('created_at', 'asc')->paginate(10, ['*'], 'reports');
        $solved = Report::with(['post.user', 'user'])->where('status', 1)->orderBy('created_at', 'asc')->paginate(10, ['*'], 'solved');
  
        return view('admin.posts.list', compact('reports', 'solved'));
    }
    public function listViolation()
    {
        $posts = Post::withFlagged()->where('is_flag', true)->latest()->paginate(10);
        return view('admin.posts.list-violation', compact('posts'));
    }
    public function approvePost($id)
    {
        $post = Post::withFlagged()->findOrFail($id);
        $post->is_flag = false;
        $post->save();
        
        Mail::to($post->user->email)->queue(new ApproveViolationPostMail($post->user->name, $post->title, $post->create_at, $post->des));
        return response()->json(['success' => true]);
    }
    public function detailReport(Report $report)
    {
        $post = Post::findOrFail($report->post_id);
        $likesCount = Like::where('post_id', $report->post_id)->count();
        $reporters = Report::where('post_id', $post->id)->with('user')->get();

        return view('admin.posts.report-detail', compact('post', 'report', 'reporters', 'likesCount'));
    }
    public function doneReportPost(Report $report)
    {
        $post_id=$report->post_id;
        Report::where('post_id', $post_id)->update(['status' => 1]);

        return response()->json(['success' => true]);
    }
    public function detailPost(Post $post)
    {
        $post = Post::findOrFail($post->id);
        $likesCount = Like::where('post_id', $post->id)->count();
        return view('admin.posts.detail', compact('post',  'likesCount'));
    }
    public function detailFlaggedPost($id)
    {
        $post = Post::withFlagged()->findOrFail($id);
        $likesCount = Like::where('post_id', $post->id)->count();
        return view('admin.posts.detail', compact('post',  'likesCount'));
    }
    public function deleteReportPost(Report $report)
    {
        DB::beginTransaction();
        try {

            $post = Post::with('user')->findOrFail($report->post_id);
            $user = $post->user;
            $user_name = $user->name;
            $email = $user->email;
            $report_reason = $report->reason;
            $report_title = $report->title;

            $report_detail = '';
            if ($report->detail) {
                $report_detail = $report->detail;
            }
            $report->status = 1;
            $report->save();
            Mail::to($email)->queue(new DeletePostMail($report_title, $report_reason, $report_detail, $user_name));
            $post->delete();
            DB::commit();
            return response()->json(["success" => true, "message" => "Đã gửi thông báo đến người dùng!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => "Có lỗi xảy ra: " . $e->getMessage()
            ]);
        }
    }

    public function deletePost(Post $post)
    {
        $user = $post->user;
        $post_title = $post->title;
        Mail::to($user->email)->queue(new AdminDeletePostMail($post_title, $user));
        $post->delete();
        return response()->json(['success' => true, 'message' => 'Xóa thành công!'], 200);
    }
    public function deleteMultiPost(Request $request)
    {
        $ids = $request->input('ids');
        $posts = Post::whereIn('id', $ids)->get();

        foreach ($posts as $post) {
            $post_title = $post->title;
            if ($post->user) {
                Mail::to($post->user->email)->queue(new AdminDeletePostMail($post_title, $post->user->only('name')));
            }
        }
        Post::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => 'Xóa thành công!'], 200);
    }
}