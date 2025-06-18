<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

use App\Models\Group;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeleteGroupMail;
use Illuminate\Http\Request;
use App\Mail\ActiveGroupMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminGroupController extends Controller
{
  //
  public function groupList(Request $request)
  {
    $query = Group::with('creator');
    if ($request->has('name') && $request->name) {
      $query->whereHas('creator', function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->name . '%');
      });
    }
    if ($request->has('member_count') && $request->member_count) {

      $query->where('member_count', $request->member_count);
    }
    if ($request->has('is_active') && $request->is_active) {

      $query->where('is_active', $request->is_active);
    }
    $groups = $query->latest()->paginate(10);
    return view('admin.group.list', compact('groups'));
  }

  public  function inactiveGroup(Group $group)
  {
    $group->is_active = false;
    $group->save();
    $user = $group->creator;
    Mail::to($user->email)->queue(new DeleteGroupMail($user, $group));
    return response()->json(['success' => true, 'message' => 'Vô hiệu nhóm thành công! ']);
  }
  public function inactiveMulti(Request $request)
  {
    $ids = $request->input('ids');
    $groups = Group::whereIn('id', $ids)->get();
    Group::whereIn('id', $ids)->update(['is_active' => false]);
    foreach ($groups as  $group) {
      $user = $group->creator;
      if ($user) {
        Mail::to($user->email)->queue(new DeleteGroupMail($user, $group));
      }
    }
    return response()->json(['success' => true, 'message' => 'Vô hiệu nhóm thành công! ']);
  }

  public  function activeGroup(Group $group)
  {
    $group->is_active = true;
    $group->save();
    $user = $group->creator;
    Mail::to($user->email)->queue(new ActiveGroupMail($user, $group));
    return response()->json(['success' => true, 'message' => 'Kích hoạt nhóm thành công! ']);
  }

  public function detailGroup(Group $group)
  {
    $posts = Post::query()->where('group_id', $group->id)->paginate(6);

    $members = $group->users;
    return view('admin.group.detail', compact('group', 'posts', 'members'));
  }
}