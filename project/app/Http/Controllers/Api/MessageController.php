<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;

use App\Notifications\MessageNotification;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Like;
use App\Models\User;
use App\Models\Friendship;
use App\Models\Message;
use App\Models\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{


  public  function chat(User $sender, User $receiver)
  {
    if (!$receiver) {
      return redirect()->back()->with('error', 'Không tìm thấy người nhận!');
    }
    $senderId = $sender->id;
    $receiverId = $receiver->id;
    $messages = Message::where(function ($query) use ($senderId, $receiverId) {
      $query->where('sender_id', $senderId)
        ->where('receiver_id', $receiverId);
    })->orWhere(function ($query) use ($senderId, $receiverId) {
      $query->where('sender_id', $receiverId)
        ->where('receiver_id', $senderId);
    })->orderBy('created_at', 'asc')->get();

    return view('friends.message', compact('receiver', 'sender', 'messages'));
  }

  public function sendMess(Request $request, User $sender, User $receiver)
  {
    $validate = Validator::make($request->all(), [
      "content" => 'required|string',
    ]);
    if ($validate->fails()) {
      return response()->json(["success" => false, "message" => "Hiện tại không thể gửi tin nhắn!"]);
    }
    $message = new Message();
    $message->content = $request->input('content');
    $message->sender_id = $sender->id;
    $message->receiver_id = $receiver->id;
    $message->save();
    broadcast(new MessageSent($message))->toOthers();
    $receiver->notify(new MessageNotification($message));
    return response()->json(["success" => true, "message" => $message, "sender" => $sender, "receiver" => $receiver]);
  }

  public function markAsRead($id){
   
     $notification = Auth::user()->notifications->where('id', $id)->first();
      $notification->markAsRead();
      
    return response()->json(['success' => true]);
   
  }
}