<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Announcement;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAnnouncementController extends Controller
{
     public function createAnnouncement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|required',
            'content' => 'string|required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $ann = new Announcement();
        $ann->title = $request->input('title');
        $ann->content = $request->input('content');
        $ann->is_active = true;
        $ann->save();
        return response()->json(['success' => true, 'message' => 'Tạo thông báo thành công']);
    }

    public  function delete(Announcement $ann)
    {
        $ann->delete();
        return response()->json(['success' => true, 'message' => 'Xóa thông báo thành công']);
    }
    public  function inActiveAnn(Announcement $ann)
    {
        $ann->is_active = false;
        $ann->save();
        return response()->json(['success' => true, 'message' => 'Tắt kích hoạt thông báo thành công']);
    }
    public  function activeAnn(Announcement $ann)
    {
        $ann->is_active = true;
        $ann->save();
        return response()->json(['success' => true, 'message' => 'Kích hoạt thông báo thành công']);
    }
    public function index()
    {
        $in_active_announcements = Announcement::query()->where('is_active', false)->paginate(10);
        $active_announcements = Announcement::query()->where('is_active', true)->paginate(10);
        return view('admin.announcement.list', compact('in_active_announcements', 'active_announcements'));
    }
}