<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>YouVerse - Vụ trũ bạn viết</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top py-2">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#">
        <img src="{{ asset('assets/images/blog-logo.png') }}" alt="Logo" width="40" height="40" class="rounded-circle">
        <span class="ml-2 font-weight-bold text-dark">YouVerse</span>
      </a>

      <div class="action d-flex align-items-center gap-3">
        <a href="{{ route('login') }}"
          class="text-dark text-decoration-none d-flex align-items-center justify-content-center fs-4">
          <i style="font-size:25px;" class="fas fa-sign-in-alt"></i>
        </a>
        <a href="{{ route('register') }}"
          class="text-dark text-decoration-none d-flex align-items-center justify-content-center fs-4">
          <i style="font-size:25px;" class="fas fa-user-plus"></i>
        </a>
      </div>

    </div>
  </nav>


  <div id="homeCarousel" class="carousel slide mt-5 pt-4" data-ride="carousel" style="padding:0;">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="{{ asset('assets/images/slide1.png') }}" class="d-block w-100"
          style="height: 400px; object-fit: cover;">
      </div>
      <div class="carousel-item">
        <img src="{{ asset('assets/images/slide2.png') }}" class="d-block w-100"
          style="height: 400px; object-fit: cover;">
      </div>
      <div class="carousel-item">
        <img src="{{ asset('assets/images/slide2.png') }}" class="d-block w-100"
          style="height: 400px; object-fit: cover;">
      </div>
    </div>

    <a class="carousel-control-prev" href="#homeCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>

    <a class="carousel-control-next" href="#homeCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <div class="container-fluid">
    <div class="row sidebar " style="margin-left:20px;margin-right:20px">
      <div class="col-md-9 mt-5">
        <div class="row">
          @foreach ($famousPosts as $f)
        @include('partials.blog', ['post' => $f])
      @endforeach
        </div>
      </div>
      <div class="col-md-3  mt-5 sidebar welcome">
        <div class="sidebar-content">
          <h5 class="text-center mb-3 mt-2">Top người viết</h5>
          <ul class="list-group border-0">
            @foreach ($topUsers as $user)
        <li class="list-group-item d-flex align-items-center border-0 border-bottom py-3 px-2">
          <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle shadow-sm me-3"
          style="width: 50px; height: 50px; object-fit: cover; margin-right:5px">
          <div>
          <div>{{ $user->name }}</div>
          <div class="text-muted" style="font-size: 0.9em;">
            {{ number_format($user->follower_count) }} người theo dõi
          </div>
          </div>
        </li>
      @endforeach
          </ul>
        </div>
      </div>
    </div>

    <div class="row mt-5 mb-5 text-center info-section">
      <div class="col-12 mb-5">
        <h2 class="fw-bold" style="font-size: 2.8rem; letter-spacing: 1.2px;">
          Vì sao nên tham gia <span class="text-primary">YouVerse</span>?
        </h2>
        <p class="text-secondary" style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">
          Khám phá những giá trị cộng đồng, kết nối và chia sẻ
        </p>
      </div>

      <div class="row bg-light py-5 px-4 rounded-4 shadow-sm mx-1">
        <div class="col-md-4 mb-4">
          <div class="card h-100 border-0 rounded-4 p-4 text-center bg-white shadow">
            <div class="mb-4">
              <i class="fas fa-users fa-3x text-success" style="transition: transform 0.3s ease;"></i>
            </div>
            <h5 class="fw-semibold mb-3" style="font-size: 1.3rem; letter-spacing: 0.05em;">Kết nối cộng
              đồng</h5>
            <p class="text-muted px-2">
              Giao lưu, tìm bạn cùng sở thích, tạo nhóm chia sẻ bài viết.
            </p>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card h-100 border-0 rounded-4 p-4 text-center bg-white shadow">
            <div class="mb-4">
              <i class="fas fa-pen-nib fa-3x text-primary" style="transition: transform 0.3s ease;"></i>
            </div>
            <h5 class="fw-semibold mb-3" style="font-size: 1.3rem; letter-spacing: 0.05em;">Viết & chia sẻ
            </h5>
            <p class="text-muted px-2">
              Đăng blog, cảm nghĩ mỗi ngày và lan toả giá trị.
            </p>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card h-100 border-0 rounded-4 p-4 text-center bg-white shadow">
            <div class="mb-4">
              <i class="fas fa-bolt fa-3x text-warning" style="transition: transform 0.3s ease;"></i>
            </div>
            <h5 class="fw-semibold mb-3" style="font-size: 1.3rem; letter-spacing: 0.05em;">Tương tác
              nhanh</h5>
            <p class="text-muted px-2">
              Like, bình luận, theo dõi và nhắn tin trực tiếp.
            </p>
          </div>
        </div>
      </div>
    </div>


    <div
      style="background: linear-gradient(135deg, #5268ad, #a3b8ff); padding: 40px 20px;  margin: 40px auto; box-shadow: 0 8px 20px rgba(106, 140, 255, 0.4);"
      class="text-center text-white">
      <h4 class="mb-3" style="font-weight: 700; font-size: 1.8rem; letter-spacing: 1px;">Gia nhập cộng đồng
        <span style="color: #085ddd;">YouVerse</span> miễn phí!
      </h4>
      <a href="{{ route('register') }}" class="btn btn-light btn-lg"
        style="font-weight: 600; padding: 12px 30px; border-radius: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: background-color 0.3s ease;">
        Tham gia ngay
      </a>
    </div>

  </div>
  @include('components.footer')

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('.card').forEach(card => {
      card.addEventListener('mouseenter', () => {
        card.querySelector('i').style.transform = 'scale(1.2)';
      });
      card.addEventListener('mouseleave', () => {
        card.querySelector('i').style.transform = 'scale(1)';
      });
    });
  </script>

</body>

</html>