<nav id="sidebar" class="sidebar bg-light p-3"
  style="border-radius:25px;margin-left:10px;margin-right:10px;width: 250px; min-height: 100vh; position: relative; transition: width 0.3s;">

  <!-- Toggle Button -->
  <button id="toggleSidebar" class="btn btn-light border" style="
    position: absolute;
    top: 8px;
    right: 10px;
    width: 26px;
    height: 26px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    opacity: 0.6;
    z-index: 100;
    transition: right 0.3s, opacity 0.3s;
  ">
    <i class="fas fa-bars" style="font-size: 14px;"></i>
  </button>



  <ul class="nav flex-column" style="margin-top: 60px;">

    <li class="nav-item">
      <a id="home" class="nav-link text-dark d-flex align-items-center gap-2" href="{{ url('home')  }}">
        <img src="{{ asset('assets/images/home.png') }}" style="width: 24px;" alt="Trang chủ">
        <span>Trang chủ</span>
      </a>
    </li>
    <li class="nav-item">
      <a id="home" class="nav-link text-dark d-flex align-items-center gap-2" href="{{ url('forum')  }}">
        <img src="{{ asset('assets/images/forum.png') }}" style="width: 24px;" alt="Diễn đàn">
        <span>Thảo luận</span>
      </a>
    </li>


    <li class="nav-item">
      <a id="group" class="nav-link text-dark d-flex align-items-center gap-2"
        href="{{ url('group/' . auth()->id()) }}">
        <img src="{{ asset('assets/images/group.png') }}" style="width: 24px;" alt="Nhóm">
        <span>Nhóm</span>
      </a>
    </li>


    <li class="nav-item">
      <a id="friend" class="nav-link text-dark d-flex align-items-center gap-2"
        href="{{ url('friend/' . auth()->id()) }}">
        <img src="{{ asset('assets/images/friend.png') }}" style="width: 24px;" alt="Bạn bè">
        <span>Bạn bè</span>
      </a>
    </li>


    <li class="nav-item">
      <a id="group" class="nav-link text-dark d-flex align-items-center gap-2"
        href="{{ url('folder/' . auth()->id()) }}">
        <img src="{{ asset('assets/images/folder.png') }}" style="width: 24px;" alt="Thư mục">
        <span>Thư mục</span>
      </a>
    </li>

    <li class="nav-item">
      <a id="statistic" class="nav-link text-dark d-flex align-items-center gap-2"
        href="{{ url('statistic/' . auth()->id()) }}">
        <img src="{{ asset('assets/images/static.png') }}" style="width: 24px;" alt="Thống kê">
        <span>Thống kê</span>
      </a>
    </li>


    <li class="nav-item">
      <a class="nav-link text-dark d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="collapse"
        href="#managementMenu" role="button" aria-expanded="false" aria-controls="managementMenu">
        <img src="{{ asset('assets/images/manage.png') }}" style="width: 24px;" alt="Quản lý">
        <span>Quản lý</span>
      </a>
      <div class="collapse ps-4" id="managementMenu">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ url('post/my-posts')}}">Bài viết </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ url('management/' . auth()->id()) }}">Chia sẻ - Chủ đề</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ url('post/schedule')}}">Hẹn lịch đăng </a>
          </li>
        </ul>
      </div>
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
      sidebar.style.width = '85px';
      if (content) content.style.marginLeft = '85px';
    } else {
      sidebar.style.width = '250px';
      if (content) content.style.marginLeft = '250px';
    }
  });
</script>