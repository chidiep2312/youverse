<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Đăng ký tài khoản YOUVERSE</title>
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
            <h4>Bạn chưa có tài khoản ?</h4>
            <h6 class="font-weight-light">Sau đây là các bước đăng ký đơn giản nè !</h6>
            <form id="registerFrm" class="pt-3">
              @csrf
              <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Username">
              </div>
              <div class="form-group">
                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email">
              </div>
              <div class="form-group">
                <input type="password" class="form-control form-control-lg" id="password" name="password"
                  placeholder="Password">
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-primary">Đăng ký</button>
              </div>
              <div class="text-center mt-4 font-weight-light">
                Đã có tài khoản rồi? <a href="{{route('login')}}" class="text-primary">Đăng nhập</a>
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
    document.getElementById('registerFrm').addEventListener('submit', function (event) {
      event.preventDefault();
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      fetch('/register', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          name,
          email,
          password
        })
      })
        .then(response => response.json())
        .then(data => {
          console.log(data);
          if (data.status == 'success') {
            alert("Đăng ký thành công!");
            window.location.href = '/login';
          } else {
            let errorMessages = '';
            Object.values(data.message).forEach(fieldErrors => {
              fieldErrors.forEach(err => {
                errorMessages += err + '\n';
              });
            });
            alert(errorMessages);
          }
        })
        .catch(error => console.error('Error:', error));
    });
  </script>

</body>

</html>