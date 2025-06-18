<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login to YOUVERSE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            <h4>Bạn đã quay lại !</h4>
            <h6 class="font-weight-light">Hãy đăng nhập để tiếp tục.</h6>
            <form id="loginFrm" class="pt-3">
              @csrf
              <div class="form-group">
                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email">
              </div>
              <div class="form-group">
                <input type="password" class="form-control form-control-lg" id="password" name="password"
                  placeholder="Password">
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Đăng
                  nhập
                </button>
              </div>
              <div class="my-2 d-flex justify-content-between align-items-center">
                <a id="forgot-pass" href="#" class="auth-link text-black">Quên mật khẩu ?
                </a>
              </div>

              <div class="text-center mt-4 font-weight-light">
                Bạn chưa có tài khoản? <a href="{{route('register')}}" class="text-primary">Đăng ký</a>
              </div>
              <div class="text-center mt-4 font-weight-light">
                <a href="{{ route('google.login') }}" id="loginGG" class="text-primary"><i class="fa-brands fa-google"
                    style="color: #d2360f;margin-right:5px;"></i> Đăng nhập với
                  tài
                  khoản Google?</a>
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

  <script>

    document.getElementById('loginFrm').addEventListener('submit', function (e) {
      e.preventDefault();
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      let auth_token = localStorage.getItem('auth_token');
      fetch('/login', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email,
          password
        })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            if (data.is_block == 'no') {
              window.location.href = '/2fa/verify';
            } else {
              window.location.href = '/login-fail';
            }
          } else {
            alert(data.message);
          }
        })
        .catch(error => console.error('Error:', error))

    })
  </script>
  <script>
    document.getElementById('forgot-pass').addEventListener('click', function () {
      window.location.href = "/password/reset-password";
    })
  </script>
</body>

</html>