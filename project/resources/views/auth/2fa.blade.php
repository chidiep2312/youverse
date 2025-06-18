<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Xác thực 2 lớp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card p-4" style="width: 100%; max-width: 400px;">
        <h4 class="mb-3 text-center">Nhập mã xác thực</h4>
        <form id="verify-form">
            @csrf
            <div class="mb-3">
                <label for="code" class="form-label">Mã 2FA</label>
                <input type="text" name="code" id="code" class="form-control" required autofocus>
                <div id="error-code" class="text-danger mt-2"></div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Xác nhận</button>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('verify-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const code = document.getElementById('code').value;
        $.ajax({
            url: `/2fa/verify-login`,
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: {
                code: code,
            },
            success: function (response) {
                console.log(response);
                if (response.success == true) {
                    localStorage.setItem('auth_token', response.token);
                    localStorage.setItem('user_id', response.user_id);
                    if (response.role == "admin") {
                        window.location.href = '/admin';
                    } else {
                        if (response.is_block == "yes") {
                            window.location.href = '/login-fail';
                        } else {
                            window.location.href = '/home';
                        }
                    }
                } else {
                    alert("Mã không đúng!");
                }
            },
            error: function (xhr, status, error) {
                console.error("Lỗi:", error);
            }
        });
    });


</script>