<?php

namespace App\Http\Controllers\Api;
use App\Models\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //

    public function reportPost(Request $request, $userId)
{
   
    $request->validate([
        'reason' => 'required|string|max:255',
        'details' => 'nullable|string',
    ]);
     $postId=$request->input('id');
    $existing = Report::where('user_id', $userId)->where('post_id', $postId)->first();

    if ($existing) {
        return response()->json(['message' => 'Bạn đã báo cáo bài viết này rồi!', 'status'=>false]);
    }
    $report=new Report();
    $report->user_id= $userId;
    $report->post_id=$postId;
    $report->reason=$request->reason;
    $report->details=$request->detail??null;
    $report->status=0;
    $report->save();
    return response()->json(['message' => 'Đã gửi báo cáo. Cảm ơn bạn!', 'status'=>true]);
}

}