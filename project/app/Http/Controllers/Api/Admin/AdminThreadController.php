<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Thread;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeleteThreadMail;
use App\Mail\PinTopicMail;
class AdminThreadController extends Controller
{
    //
    public function list(Request $request)
    {
        $query = Thread::with('user');

        if ($request->has('searchInput') && $request->input('searchInput')) {
            $search = $request->input('searchInput');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        }
        if ($request->has('created_at') && $request->created_at) {
            $query->whereDate('created_at', $request->created_at);
        }
       
        $clone= clone $query;
         if ($request->has('is_pinned') && $request->is_pinned) {
            $clone->where('is_pinned', $request->is_pinned);
        }
        $threads = $query->where('type','status')->latest()->paginate(10);
        $topics = $clone->where('type','topic')->latest()->paginate(10);
       
        return view('admin.thread.list', compact('threads','topics'));
    }
    public function detailTopic(Thread $topic){
        return view('admin.thread.detail-topic',compact('topic'));
    }
    public  function pin(Thread $topic){
        $topic->is_pinned=true;
        $topic->save();
          Mail::to($topic->user->email)->queue(new PinTopicMail($topic->user->name,$topic->title));
        return response()->json(['success'=>true]);
    }
     public  function unpin(Thread $topic){
        $topic->is_pinned=false;
        $topic->save();
        return response()->json(['success'=>true]);
    }
    public function delete(Thread $thread)
    {
        $user = $thread->user;
        $user_name=$user->name;
        $thread_id = $thread->id;
        $thread_content = $thread->content;
         Mail::to($user->email)->queue(new DeleteThreadMail($thread_id, $thread_content, $user_name));
        $thread->delete();
        return response()->json(['success' => true, 'message' => 'Xóa thành công!'], 200);
    }
}