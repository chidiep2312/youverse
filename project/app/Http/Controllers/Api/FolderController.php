<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Folder;

class FolderController extends Controller
{
    //
    public function index(User $userId)
    {
        $folders = $userId->folders()->get();
        $tags = Tag::all();

        return view('folder.index', compact('folders', 'tags'));
    }
    public function userView(Folder $folder,User $userId)
    {
       $posts=Post::query()->where('folder_id',$folder->id)->where('user_id',$userId->id)->paginate(6);
 
       return view('folder.user-view', compact('folder', 'posts'));
    }
    public function save(User $userId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
            'des' => 'string|required',
            'tag_id' => 'integer',
            'bgr' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        if ($request->hasFile('bgr')) {
            $imgPath = $request->file('bgr')->store('images/groups', 'public');
        } else {
            $imgPath = '';
        }
        Folder::create([
            "user_id" => $userId->id,
            "name" => $request->input('name'),
            "des" => $request->input('des'),
            "tag_id" => $request->input('tag_id'),
            "bgr" => $imgPath

        ]);

        return response()->json(['message' => "Tạo thư mục mới thành công!", "status_code" => 200]);
    }
    public function detail(User $user, Folder $folder)
    {
        $posts = Post::query()->where('folder_id', $folder->id)->paginate(6);

        return view('folder.detail', compact('folder', 'user', 'posts'));
    }
    public function delete(Folder $folder)
    {
        if (Auth::id() == $folder->user_id) {
            $folder->delete();
            return response()->json(['success' => true, 'message' => 'Xóa thư mục thành công.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Bạn không thể xóa!.']);
        }
    }
}