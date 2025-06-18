<?php

namespace App\Http\Controllers\Api;
use App\Notifications\leaveNotification;
use Illuminate\Support\Facades\Session;
use App\Notifications\InviteNofication;
use App\Notifications\ApproveNotification;
use App\Notifications\RejectNotification;
use App\Notifications\RemoveNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Group;
use App\Models\Friendship;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    //
    public function index(User $userId)
    {
        $groups = $userId->groups()->where('is_active', true)->get();


        return view('group.index', compact('groups'));
    }

    public function save(User $userId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'bgr' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), "status" => 'false']);
        }

        if ($request->hasFile('bgr')) {
            $imgPath = $request->file('bgr')->store('images/groups', 'public');
        } else {
            $imgPath = '';
        }
        $group = Group::create([
            "user_id" => $userId->id,
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "member_count" => 1,
            "is_active" => true,
            "bgr" => $imgPath,
        ]);

        $group->users()->attach($userId->id);
        return response()->json(['message' => "Tạo nhóm mới thành công!", "status" => 'success']);
    }
    public function updateGroup(Group $group,  Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'bgr' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if ($request->filled('name')) {
            $group->name = $request->input('name');
        }
        if ($request->filled('description')) {
            $group->description = $request->input('description');
        }
        if ($request->hasFile('bgr')) {
            $imgPath = $request->file('bgr')->store('images/groups', 'public');
            $group->bgr = $imgPath;
        }
        $group->member_count=  $request->input('member_count');
        $group->save();
        return response()->json(["success" => true]);
    }
    public function detail(User $user, Group $group)
    {
        $posts = Post::query()->where('group_id', $group->id)->paginate(6);
        return view('group.detail', compact('group', 'user', 'posts'));
    }
    public function leave(User $user, Group $group)
    {
        $group->users()->detach($user->id);
        $group->decrement('member_count');
        $creator_id=$group->user_id;
        $creator=User::findOrFail( $creator_id);
          $creator->notify(new leaveNotification($group,$user));
        return response()->json([
            'message' => 'Rời nhóm thành công!',
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
    }
    public function delete(Group $group)
    {
        $group->users()->detach();
        $group->delete();
        return response()->json(['message' => 'Xóa nhóm thành công.']);
    }

    public  function find(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer|required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $group = Group::query()->where('id',  $request->input('id'))->where('is_active', true)->get();
        if ($group->isEmpty()) {
            return response()->json(['success' => false]);
        }
        return response()->json(['success' => true, 'group' => $group]);
    }

    public function result(Group $group)
    {
        $invite = session('invited_group_id') == $group->id;
        $inviteGroupId = Session::get('invited_group_id');
      
        return view('group.result', compact('group', 'invite'));
    }
    public function manage(Group $group)
    {
        $id = Auth::id();
        $join_requests = $group->joinRequests()->get();
        $members = $group->members()->get();
       
        $invite_members = Friendship::query()
            ->where(function ($query) use ($id) {
                $query->where('user_id',         $id)
                    ->orWhere('friend_id',         $id);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function ($friendship) use ($id) {
                return $friendship->user_id === $id
                    ? $friendship->friend
                    : $friendship->user;
            });
        return view('group.manage', compact('group', 'members', 'join_requests', 'invite_members'));
    }
    public function join(User $user, Group $group)
    {
        $check = $group->users()->where('user_id', $user->id)->first();

        if ($check) {
            $status =   $check->pivot->status;
            if ($status === 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã gửi yêu cầu tham gia nhóm này!'
                ]);
            } elseif ($status === 'accepted') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã là thành viên của nhóm này!'
                ]);
            }
        }

        $group->users()->attach($user->id, ['status' => 'pending']);
        return response()->json([
            'success' => true,
            'message' => 'Yêu cầu tham gia nhóm đã được gửi, vui lòng chờ phê duyệt.'
        ]);
    }
    public function approve(Group $group, Request $request)
    {
        $group->users()->updateExistingPivot($request->input('user_id'), ['status' => 'accepted']);
        //gửi thông báo cho người xin vào nhóm
        $user = User::findOrFail($request->input('user_id'));
        $user->notify(new ApproveNotification($group));
        $group->increment('member_count');

        return response()->json(['success' => true]);
    }
    public function reject(Group $group, Request $request)
    {
        $group->users()->updateExistingPivot($request->input('user_id'), ['status' => 'rejected']);
        $user = User::findOrFail($request->input('user_id'));
        $user->notify(new RejectNotification($group));
        return response()->json(['success' => true]);
    }
    public function remove(Group $group, Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $user = User::findOrFail($request->input('user_id'));
        $user->notify(new RemoveNotification($group));
        $group->users()->detach($user_id);
        $group->decrement('member_count');
        return response()->json(['success' => true]);
    }
    public function invite(Request $request, User $user)
    {
        $group = Group::findOrFail($request->input('group_id'));
        $user->notify(new InviteNofication($group));
        return response()->json(["success" => true, "user" => ['id' => $user->id, 'name' => $user->name]]);
    }
    public function inviteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(["success" => false, "errors" => $validator->errors()]);
        }
        $group = Group::findOrFail($request->input('group_id'));
        $user = User::query()->where('email', $request->input('email'))->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tồn tại người dùng có email này!']);
        }
        $user->notify(new InviteNofication($group));
        return response()->json(["success" => true, "user" => ['id' => $user->id, 'name' => $user->name]]);
    }

    public function markAsRead($id, Group $group)
    {
        $notification = Auth::user()->notifications->where('id', $id)->first();
        $notification->markAsRead();
        Session::put('invited_group_id', $group->id);
        return response()->json(['success' => true]);
    }
    public function markAsReadSimple($id)
    {
        $notification = Auth::user()->notifications->where('id', $id)->first();
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }
    public function acceptInvite(Group $group)
    {
        $group->users()->attach(Auth::id(), ['status' => 'accepted']);
        $group->increment('member_count');
          session()->forget('invited_group_id');
        return response()->json(['success' => true, 'id' => $group->id]);
    }
}