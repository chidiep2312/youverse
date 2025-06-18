<h4 style="color:red;">Chào {{ $user->name }},</h4><br>

Chúng tôi xin thông báo rằng nhóm của bạn {{ $group->id }} - {{ $group->name }} đã vi phạm chính sách của chúng tôi.
Chúng tôi đã chặn hoạt động nhóm của bạn vào ngày
{{ now()->format('d/m/Y') }}.<br>.
Nếu bạn cho rằng đây là sự nhầm lẫn hoặc muốn khiếu nại, vui lòng liên hệ đội ngũ hỗ trợ qua email
support@youverse.com hoặc truy cập trang liên hệ để gửi phản hồi. <br>

------------
Trân trọng,<br>
Đội ngũ quản trị<br>
YouVerse - You write your universe