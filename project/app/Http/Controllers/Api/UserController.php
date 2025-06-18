<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Folder;
use App\Models\Friendship;
use App\Models\Thread;
use App\Models\Block;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\FollowNotification;

class UserController extends Controller
{
    //

    public function getAvatar()
    {
        $id = request()->input('user');
        $u = User::findOrFail($id);

        return response()->json(['status_code' => 200, 'ava' => asset('storage/' . $u->avatar)]);
    }
    public function show(User $user)
    {

        $tags = Tag::all();
        $folders = Folder::where('user_id', $user->id)->get();
        $threads = Thread::query()->where('user_id', $user->id)->where('type', 'status')->latest()->get();
        $topics = Thread::where('user_id', $user->id)->where('type', 'topic')->latest()->get();
        $newest_posts = Post::where('user_id', $user->id)->where('status', 'published')->whereNull('group_id')->paginate(5);

        return view('user.personal', compact('user', 'topics', 'tags', 'newest_posts', 'threads', 'folders'));
    }
    public function tagPosts($id, $userId)
    {
        $tag_posts = Post::where('tag_id', $id)->where('user_id', $userId)->get();
        return response()->json(["success" => true, "posts" => $tag_posts]);
    }
    public function showFriendPage(User $user)
    {
        $newest_posts = Post::where('user_id', $user->id)->where('status', 'published')->paginate(5);
        $folders = Folder::query()->where('user_id', $user->id)->get();
        $threads = Thread::query()->where('user_id', $user->id)->where('type', 'status')->get();
        $topics = Thread::query()->where('user_id', $user->id)->where('type', 'topic')->get();
        return view('user.friend-page', compact('user', 'folders', 'topics', 'threads', 'newest_posts'));
    }




    public function  searchUser(User $user)
    {
        $auth = Auth::user();
        return view('user.search', compact('user', 'auth'));
    }

    public function follow(Request $request)
    {
        $followId = $request->input('friendId');
        //kiem tra da follow chua
        $userId = Auth::user()->id;
        if ($followId == $userId) {
            return response()->json(['success' => false, 'message' => "Bạn không thể theo dõi bạn"]);
        }
        $followed = Friendship::where(function ($query) use ($userId, $followId) {
            $query->where(function ($subQuery) use ($userId, $followId) {
                $subQuery->where('user_id', $userId)
                    ->where('friend_id', $followId);
            })
                ->orWhere(function ($subQuery) use ($userId, $followId) {
                    $subQuery->where('user_id', $followId)
                        ->where('friend_id', $userId);
                });
        })->first();

        if ($followed) {
            if ($followed->status == 'pending') {
                return response()->json(['success' => false, 'message' => "Bạn đã  yêu cầu kết bạn!"]);
            }
            if ($followed->status == 'accepted') {
                return response()->json(['success' => false, 'message' => "Các bạn đã là bạn bè!"]);
            }
            if ($followed->status == 'declined') {
                return response()->json(['success' => false, 'message' => "Hmm bạn không có quyền theo dõi người này này!"]);
            }
        }
        $friendShip = new Friendship();
        $friendShip->user_id = $userId;
        $friendShip->friend_id = $followId;
        $friendShip->status = 'pending';
        $friendShip->save();
        $receiver = User::findOrFail($followId);
        $receiver->notify(new  FollowNotification($friendShip));
        return response()->json(['success' => true, 'message' => 'Theo dõi thành công!']);
    }
    public function updateAvatar(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()]);
        }
        $imgPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            $imgPath = $request->file('avatar')->store('images/user', 'public');
        } else {
            return response()->json(["success" => false, "mesage" => "Cập nhật ảnh đại diện không thành công!"]);
        }
        $user->avatar = $imgPath;
        $user->save();
        return response()->json(["success" => true, "message" => "Cập nhật ảnh đại diện thành công!"]);
    }

    public function updateBgr(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'bgr' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()]);
        }
        $imgPath = $user->avatar;
        if ($request->hasFile('bgr')) {
            $imgPath = $request->file('bgr')->store('images/user', 'public');
        } else {
            return response()->json(["success" => false, "mesage" => "Cập nhật thất bại!"]);
        }
        $user->bgr = $imgPath;
        $user->save();
        return response()->json(["success" => true, "message" => "Cập nhật thành công!"]);
    }
    public function updateName(User $userId, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $userId->name = $request->input('name');
        $userId->update();

        return response()->json(['message' => "Đổi tên thành công", 'status_code' => 200]);
    }

    public function changeSlogan(User $user, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'slogan' => 'string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user->slogan = $request->input('slogan');

        $user->update();

        return response()->json(['message' => "Cập nhật thành công !", 'status_code' => 200]);
    }
    public function unfriend($friend_id)
    {


        $friendship = Friendship::where(function ($query) use ($friend_id) {
            $query->where('user_id', Auth::id())
                ->where('friend_id', $friend_id);
        })->orWhere(function ($query) use ($friend_id) {
            $query->where('user_id', $friend_id)
                ->where('friend_id', Auth::id());
        })->where('status', 'accepted')->first();
        if (!$friendship) {
            return response()->json(['message' => "Chưa là bạn bè", 'status' => 'fail']);
        }
        $friendship->status = "pending";
        $friendship->save();
        return response()->json(['message' => "Hủy kết bạn thành công !", 'status' => 'success']);
    }
    public function block($friend_id)
    {

        $friendship = Friendship::where(function ($query) use ($friend_id) {
            $query->where('user_id', operator: Auth::id())
                ->where('friend_id', $friend_id);
        })->orWhere(function ($query) use ($friend_id) {
            $query->where('user_id', $friend_id)
                ->where('friend_id', Auth::id());
        })->first();
        if (Auth::id() == $friend_id) {
            return response()->json(['message' => 'Không thể tự chặn chính mình', 'status' => 'false']);
        }

        Block::updateOrCreate([
            'user_id' => Auth::id(),
            'blocked_user_id' => $friend_id,
        ]);
        if ($friendship) {
            $friendship->status = 'declined';
            $friendship->save();
        }
        return response()->json(['status' => 'success', 'message' => 'Chặn người dùng thành công']);
    }
    public function unblock($friend_id)
    {
        $friendship = Friendship::where(function ($query) use ($friend_id) {
            $query->where('user_id', operator: Auth::id())
                ->where('friend_id', $friend_id);
        })->orWhere(function ($query) use ($friend_id) {
            $query->where('user_id', $friend_id)
                ->where('friend_id', Auth::id());
        })->first();
        Block::where('user_id', Auth::id())
            ->where('blocked_user_id', $friend_id)
            ->delete();
        if ($friendship) {
            $friendship->delete();
        }


        return response()->json(['status' => 'success', 'message' => 'Bỏ chặn người dùng thành công']);
    }
}
