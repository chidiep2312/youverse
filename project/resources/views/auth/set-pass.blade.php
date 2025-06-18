<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Đặt lại mật khẩu</title>
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css')}}">
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png')}}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="{{ asset('assets/images/blog-logo.png')}}" alt="logo">
              </div>

              <form id="changePassFrm" class="pt-3">
                @csrf
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="old_password" name="old_password"
                    placeholder="Nhập mật khẩu cũ">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="new_password" name="new_password"
                    placeholder="Nhập mật khẩu mới">
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary">Thay đổi</button>
                </div>

              </form>
            </div>
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

</html>
<script>
  document.getElementById('changePassFrm').addEventListener('submit', function (e) {
    e.preventDefault();
    let id = localStorage.getItem('user_id');
    let auth_token = localStorage.getItem('auth_token');
    let oldp = document.getElementById('old_password').value;
    let newp = document.getElementById('new_password').value;
    fetch('/password/change-password/' + id, {
      "method": 'POST',
      "headers": {
        'X-CSRF-TOKEN': '{{csrf_token()}}',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${auth_token}`
      },
      body: JSON.stringify({
        old_password: oldp,
        new_password: newp
      })
    }).then(response => response.json())
      .then(data => {
        if (data.success == true) {
          alert(data.message);
          window.location.href = 'home';
        } else {
          alert(data.error);
        }
      }).catch(error => console.error('Error:', error));

  })
</script>