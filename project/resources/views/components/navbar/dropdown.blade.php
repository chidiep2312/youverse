<li class="nav-item nav-profile dropdown">
  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
    <div id="user-avatar">
      <img src="" alt="profile" />
    </div>
  </a>
  <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
    <a id="user_page" class="dropdown-item" href="#">
      <button type="button" style="background-color: transparent; border: none;">
        <i class="mdi mdi-account"></i> Trang cá nhân
      </button>
    </a>
    <a class="dropdown-item" href="{{ route('setPass') }}">
      <button type="button" style="background-color: transparent; border: none;">
        <i class="mdi mdi-key"></i> Đặt lại mật khẩu
      </button>
    </a>
    <form id="logout">
      @csrf
      <span class="dropdown-item">
        <button type="submit" style="background-color: transparent; border: none;">
          <i class="ti-power-off text-primary"></i>
          Đăng xuất
        </button>
      </span>
    </form>
  </div>
</li>