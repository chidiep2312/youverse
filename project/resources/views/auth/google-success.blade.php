<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Google Login Redirect</title>
</head>

<body>
    <script>
        const params = new URLSearchParams(window.location.search);
        const token = params.get('token');
        const user_id = params.get('user_id');
        const role = params.get('role');
        if (token && user_id) {
            localStorage.setItem('auth_token', token);
            localStorage.setItem('user_id', user_id);

            if (role === 'admin') {
                window.location.href = '/admin';
            } else {
                window.location.href = '/home';
            }
        } else {
            alert("Lỗi đăng nhập bằng Google!");
            window.location.href = '/login';
        }
    </script>
</body>

</html>