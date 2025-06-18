<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quên mật khẩu</title>

    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css')}}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="brand-logo">
                            <img src="{{ asset('assets/images/blog-logo.png')}}" alt="logo">
                        </div>

                        <form id="setForgotPwdFrm" class="pt-3">
                            @csrf
                            <input id="user_id" type="hidden" value="{{$id}}">
                            <div class="form-group">
                                <label>Mật khẩu:</label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                    name="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label>Xác nhận lại:</label>
                                <input type="password" class="form-control form-control-lg" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm password">
                            </div>

                            <div class="mt-3">
                                <button type="submit"
                                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Đặt
                                    lại</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <script src="{{ asset('assets/js/off-canvas.js')}}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{ asset('assets/js/template.js')}}"></script>
    <script src="{{ asset('assets/js/settings.js')}}"></script>
    <script src="{{ asset('assets/js/todolist.js')}}"></script>



</body>

<script>
    document.getElementById('setForgotPwdFrm').addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('user_id').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        fetch(`/password/set-password/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                password,
                password_confirmation
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success === true) {
                    alert(data.message);
                    window.location.href = "/login";
                } else {
                    alert(data.errors);
                }
            })
            .catch(error => console.error('Lỗi:', error))
    })
</script>

</html>