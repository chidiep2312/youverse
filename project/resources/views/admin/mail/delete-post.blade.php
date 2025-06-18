<h4 style="color:red;">Chào {{ $user_name }},</h4><br>

Chúng tôi xin thông báo rằng bài viết của bạn {{ $report_title }} đã vi phạm chính sách của chúng tôi.<br>
Bài viết được báo cáo là {{ $report_reason }} -
@if($report_detail)
    {{ $report_detail }}
@endif


Nếu bạn cho rằng đây là sự nhầm lẫn hoặc muốn khiếu nại, vui lòng liên hệ đội ngũ hỗ trợ qua email<br>
support@yourdomain.com hoặc truy cập trang liên hệ để gửi phản hồi.<br>

------------<br>
Trân trọng,<br>
Đội ngũ quản trị<br>
YouVerse - You write your universe