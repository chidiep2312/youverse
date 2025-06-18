<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Post;
use App\Models\Report;
use App\Models\Group;
use App\Models\Setting;
use App\Models\Announcement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BlockUserMail;
use App\Mail\UnBlockUserMail;
use App\Mail\WarningUserMail;

use App\Mail\DeletePostMail;
use App\Mail\DeleteGroupMail;
use App\Mail\ActiveGroupMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    //
    public function listUser(Request $request)
    {
        $query = User::query()->where('role','user');
        if ($request->has('searchInput') && $request->searchInput) {
            $query->where('email', $request->searchInput)->orWhere(function ($q) use ($request) {
                $q->where('name', $request->searchInput)->orWhere('id', $request->searchInput);
            });
        }
        if ($request->has('is_block') && $request->is_block) {

            $query->where('is_block', $request->is_block);
        }
        $users = $query->paginate(10);

        return view('admin.users.list', compact('users'));
    }
    public function detail(User $user)
    {
        $posts = $user->posts()->count();
        return view('admin.users.detail', compact('user', 'posts'));
    }

    public function blockUser(User $user)
    {
        $user->is_block = 'yes';
        $user->save();
        Mail::to($user->email)->queue(new BlockUserMail($user));
        return response()->json(["success" => true, "message" => "Đã khóa tài khoản này!"]);
    }

    public function unblockUser(User $user)
    {

        $user->is_block = 'no';
        $user->save();
        Mail::to($user->email)->queue(new UnBlockUserMail($user));
        return response()->json(["success" => true, "message" => "Đã gửi thông báo đến người dùng!"]);
    }
    public function sendWarningMail(User $user)
    {
        Mail::to($user->email)->queue(new WarningUserMail($user));
        return response()->json(["success" => true, "message" => "Đã gửi thông báo đến người dùng!"]);
    }
}