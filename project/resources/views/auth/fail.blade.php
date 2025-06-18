<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tài khoản bị khóa</title>
    <link rel="stylesheet" href="{{ asset('assets/css/login-fail.css') }}">
</head>

<body>
    <div class="locked-box">
        <h1>Tài khoản của bạn đã bị khóa</h1>
        <p>Vui lòng liên hệ quản trị viên để biết thêm chi tiết hoặc khôi phục tài khoản.</p>
        <a href="/contact">Liên hệ hỗ trợ</a>
        <a href="{{ route('welcome') }}">Quay lại</a>
    </div>
</body>

</html>