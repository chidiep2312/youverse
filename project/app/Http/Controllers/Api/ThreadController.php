<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Thread;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThreadController extends Controller
{
    public function detail(Thread $thread)
    {
        return view('thread.detail', compact('thread'));
    }
    public function markAsRead($id, Thread $threadId)
    {
        $notification = Auth::user()->notifications->where('id', $id)->first();
        $notification->markAsRead();
        
        return response()->json(['success' => true,'type'=>$threadId->type]);
    }
    public function save(User $userId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        Thread::create([
            "user_id" => $userId->id,
            "type" => 'status',
            "content" => $request->input('content'),

        ]);
        return response()->json(['message' => 'Thành công', 'status_code' => 200]);
    }
    public function delete(Thread $thread)
    {
        $thread->delete();

        return response()->json(['success' => true, 'message' => 'Xóa thành công!'], 200);
    }
    public function createTopic(){
        $tags=Tag::all();;
        return view('thread.create-topic',compact('tags'));
    }
     public function detailTopic(Thread $topic){
        return view('thread.topic-detail',compact('topic'));
    }
    public function saveTopic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|required',
            'content' => 'required|string',
            'tag_id' => 'nullable|integer',
         
            'type'=>'string|nullable',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $thread=Thread::create([
            "user_id" => Auth::id(),
            "content" => $request->input('content'),
            "title" => $request->input('title'),
            "tag_id"=>$request->input('tag_id'),
            "is_pinned"=>false,
            "type" => $request->input('type'),

        ]);
        return response()->json(['id'=>$thread->id,'message' => 'Thành công', 'success' => true]);
    }
}