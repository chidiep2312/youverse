<nav id="sidebar" class="sidebar bg-light p-3"
  style="border-radius:25px;margin-left:10px;margin-right:10px;width: 250px; min-height: 100vh; position: relative; transition: width 0.3s;">


  <button id="toggleSidebar" class="btn btn-light border" style="
      position: absolute;
      top: 10px;
      right: 15px;
      width: 30px;
      height: 30px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      cursor: pointer;
      transition: right 0.3s;
      z-index:100;
    ">
    <i class="fas fa-bars" style="font-size: 20px;"></i>
  </button>

  <ul class="nav flex-column" style="margin-top: 60px;">
    <!-- Trang chủ -->
    <li class="nav-item">
      <a id="home" class="nav-link text-dark d-flex align-items-center gap-2" href="{{ route('admin.dashboard')  }}">
        <img src="{{ asset('assets/images/home.png') }}" style="width: 24px;" alt="Trang chủ">
        <span>Trang chủ</span>
      </a>
    </li>

    <!-- Nhóm -->
    <li class="nav-item">
      <a id="group" class="nav-link text-dark d-flex align-items-center gap-2" href="{{route('admin.group.list') }}">
        <img src="{{ asset('assets/images/group.png') }}" style="width: 24px;" alt="Nhóm">
        <span>Nhóm</span>
      </a>
    </li>

    <!-- Bạn bè -->
    <li class="nav-item">
      <a id="friend" class="nav-link text-dark d-flex align-items-center gap-2" href="{{route('admin.user.list') }}">
        <img src=" {{ asset('assets/images/user.png') }}" style="width: 24px;" alt="Người dùng">
        <span>Người dùng</span>
      </a>
    </li>
    <li class="nav-item">
      <a id="friend" class="nav-link text-dark d-flex align-items-center gap-2" href="{{route('admin.thread.list') }}">
        <img src=" {{ asset('assets/images/thread.png') }}" style="width: 24px;" alt="Dòng chia sẻ - chủ đề">
        <span>Dòng chia sẻ - chủ đề</span>
      </a>
    </li>


    <li class="nav-item">
      <a class="nav-link text-dark d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="collapse"
        href="#managementMenu" role="button" aria-expanded="false" aria-controls="managementMenu">
        <img src="{{ asset('assets/images/post.png') }}" style="width: 24px;" alt="Bài viết">
        <span>Bài viết</span>
      </a>
      <div class="collapse ps-4" id="managementMenu">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{route('admin.post.list-post') }}">Tất cả</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{route('admin.post.list') }}">Bị báo cáo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{route('admin.post.list-violation') }}">Hệ thống vi phạm</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a id="tagt" class="nav-link text-dark d-flex align-items-center gap-2" href="{{route('admin.tag.index') }}">
        <img src=" {{ asset('assets/images/tag.png') }}" style="width: 24px;" alt="Thẻ">
        <span>Thẻ</span>
      </a>
    </li>
    <li class="nav-item">
      <a id="announcement" class="nav-link text-dark d-flex align-items-center gap-2"
        href="{{route('admin.annoucement.index') }}">
        <img src=" {{ asset('assets/images/announce.png') }}" style="width: 24px;" alt="Thông báo">
        <span>Thông báo</span>
      </a>
    </li>
    <li class="nav-item">
      <a id="friend" class="nav-link text-dark d-flex align-items-center gap-2"
        href="{{route('admin.setting.index') }}">
        <img src=" {{ asset('assets/images/setting.png') }}" style="width: 24px;" alt="Cài đặt">
        <span>Cài đặt</span>
      </a>
    </li>
    <li class="nav-item">
      <a id="admin" class="nav-link text-dark d-flex align-items-center gap-2" href="{{route('admin.personal') }}">
        <img src=" {{ asset('assets/images/account.png') }}" style="width: 24px;" alt="Tài khoản">
        <span>Tài khoản</span>
      </a>
    </li>
  </ul>
</nav>
<script>
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('toggleSidebar');
  const content = document.getElementById('main-content');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');

    if (sidebar.classList.contains('collapsed')) {
      sidebar.style.width = '70px';
      if (content) content.style.marginLeft = '70px';
    } else {
      sidebar.style.width = '250px';
      if (content) content.style.marginLeft = '250px';
    }
  });
</script>