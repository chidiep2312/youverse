<footer class="bg-dark text-white py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h5>Về trang web</h5>
        <p>{!! $setting->des !!}.</p>
      </div>
      <div class="col-md-4">
        <h5>Liên kết nhanh</h5>
        <ul class="list-unstyled">
          <li><a href="{{ route('login') }}" class="text-white">Đăng nhập</a></li>
          <li><a href="{{ route('register') }}" class="text-white">Đăng ký</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h5>Liên hệ</h5>
        <p>Email: {{ $setting->support_email }}</p>
        <p>Facebook: <a href="#" class="text-white">{{ $setting->support_email }}</a></p>
        <p>Hotline: <a href="#" class="text-white">{{ $setting->hotline }}</a></p>
      </div>
    </div>
    <hr class="bg-light">
    <div class="text-center">
      <small>© {{ now()->year }} YouVerse -Bạn vẽ vũ trụ của bạn.</small>
    </div>
  </div>
</footer>