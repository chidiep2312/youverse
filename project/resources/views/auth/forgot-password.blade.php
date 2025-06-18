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
                        <h4>Bạn quên mật khẩu ư? Hãy nhập email của bạn vào đây!</h4>
                        <form id="forgotPwdFrm" class="pt-3">
                            @csrf
                            <div class="form-group">
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    placeholder="Email">
                            </div>

                            <div class="mt-3">
                                <button type="submit"
                                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Gửi</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('forgotPwdFrm').addEventListener('submit', function (e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        fetch('/password/reset-password', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email
            })
        })
            .then(response => response.json())
            .then(response => {
                console.log(response);
                if (response.success === true) {
                    alert(response.message);
                } else {
                    let errorMessages = '';
                    Object.values(response.message).forEach(fieldErrors => {
                        fieldErrors.forEach(err => {
                            errorMessages += err + '\n';
                        });
                    });
                    alert(errorMessages);
                }
            })
            .catch(error => console.error('Lỗi:', error))
    })
</script>

</html>