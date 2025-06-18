<link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo mr-5" href="{{ route('home', ['user' => auth()->user()]) }}"><img id="logo"
        style="width:60px;height:60px;" src="{{ asset('assets/images/blog-logo.png') }}" class="mr-2" alt="logo" /></a>
    <a class="navbar-brand brand-logo-mini" href="{{ route('home', ['user' => auth()->user()]) }}"><img
        style="width:60px;height:60px;" src="{{ asset('assets/images/blog-logo.png') }}" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <a href="{{ route('post.create') }}">
      <button class="circle-button" type="button">
        <i class="mdi mdi-lead-pencil"></i>
      </button>
    </a>
    <ul class="navbar-nav mr-lg-2">
      <li class="nav-item nav-search d-none d-lg-block">
        <div class="input-group">
          <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
            <span class="input-group-text" id="search">
            </span>
          </div>
          <form id="search-form" class="d-flex align-items-center gap-2">
            @csrf
            <input type="text" class="form-control" id="navbar-search-input" placeholder="Tìm kiếm" aria-label="search"
              aria-describedby="search">
            <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
        </div>
      </li>
    </ul>

    <ul class="navbar-nav navbar-nav-right">
      @include('components.navbar.follow')
      @include('components.navbar.others')
      @include('components.navbar.dropdown')
    </ul>

  </div>
</nav>

<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
<script>
  window.Pusher = Pusher;
  window.Echo = new window.Echo.default({
    broadcaster: 'pusher',
    key: '33272353b3937148ce3e',
    cluster: 'ap1',
    forceTLS: false,
    encrypted: false,
    auth: {
      withCredentials: true
    }
  });
</script>
<script>
  const auth_token = localStorage.getItem('auth_token');
  const userId = localStorage.getItem('user_id');
  const followList = document.getElementById("follow-list");
  const count = document.getElementById('count');
  const noficationList = document.getElementById("notification-list");
  let group_id = null;

  window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
      const type = notification.type;
      const id = notification.id;
      if (type === "followback.notification") {
        let currentCount = parseInt(count.textContent) || 0;
        currentCount++;
        count.textContent = currentCount;
        count.style.display = 'flex';
        const item = `
                <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">
                    <div class="preview-item-content">
                        <a class="read" href="#" data-id="${notification.id}">
                                    <strong>  ${notification.friendship_friend}</strong> đã đồng ý kết bạn
                                </a>
                       
                    </div>
                </div>
            `;
        followList.insertAdjacentHTML("afterbegin", item);
        followBack();

      }
      if (type === "invite.notification") {
        const count = document.getElementById('noticount');
        let currentCount = parseInt(count.textContent) || 0;
        currentCount++;
        count.textContent = currentCount;
        count.style.display = 'flex';
        group_id = notification.group_id;
        const item = `
     <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">
                   
     <div class="preview-item-content">
         <a href="#" class="preview-subject font-weight-normal group-notification" data-id="${notification.id}">${notification.group_creator} Mời bạn tham gia nhóm ${notification.group_name} (${notification.group_id})!</a>
                    </div>
                </div>
   `;
        noficationList.insertAdjacentHTML("afterbegin", item);
        notificationList();
      }
      if (type === "approve.notification") {
        const count = document.getElementById('noticount');
        let currentCount = parseInt(count.textContent) || 0;
        currentCount++;
        count.textContent = currentCount;
        count.style.display = 'flex';
        group_id = notification.group_id;
        const item = `
     <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">
                   
     <div class="preview-item-content">
         <a href="#" class="preview-subject font-weight-normal other-notification" data-id="${notification.id}">${notification.group_creator} Chấp nhận yêu cầu tham gia ${notification.group_name} (${notification.group_id}) của bạn!</a>
                    </div>
                </div>
   `;
        noficationList.insertAdjacentHTML("afterbegin", item);
        notificationList();
      }
      if (type === "leave.notification") {
        const count = document.getElementById('noticount');
        let currentCount = parseInt(count.textContent) || 0;
        currentCount++;
        count.textContent = currentCount;
        count.style.display = 'flex';
        group_id = notification.group_id;
        const item = `
     <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">
                   
     <div class="preview-item-content">
         <a href="#" class="preview-subject font-weight-normal other-notification" data-id="${notification.id}">${notification.user_name} Đã rời nhóm ${notification.group_name} (${notification.group_id}) !</a>
                    </div>
                </div>
   `;
        noficationList.insertAdjacentHTML("afterbegin", item);
        notificationList();
      }
      if (type === "remove.notification") {
        const count = document.getElementById('noticount');
        let currentCount = parseInt(count.textContent) || 0;
        currentCount++;
        count.textContent = currentCount;
        count.style.display = 'flex';
        group_id = notification.group_id;
        const item = `
     <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">
                   
     <div class="preview-item-content">
         <a href="#" class="preview-subject font-weight-normal other-notification" data-id="${notification.id}">${notification.group_creator} Bạn bị mời thoát nhóm ${notification.group_name} (${notification.group_id}) !</a>
                    </div>
                </div>
   `;
        noficationList.insertAdjacentHTML("afterbegin", item);
        notificationList();
      }
      if (type === "reject.notification") {
        const count = document.getElementById('noticount');
        let currentCount = parseInt(count.textContent) || 0;
        currentCount++;
        count.textContent = currentCount;
        count.style.display = 'flex';
        group_id = notification.group_id;
        const item = `
     <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">
                   
     <div class="preview-item-content">
         <a href="#" class="preview-subject font-weight-normal other-notification" data-id="${notification.id}">${notification.group_creator} Yêu cầu tham gia ${notification.group_name} (${notification.group_id}) của bạn bị từ chối!</a>
                    </div>
                </div>
   `;
        noficationList.insertAdjacentHTML("afterbegin", item);
        notificationList();
      }
      if (type === "comment.notification") {
        const count = document.getElementById('noticount');
        let currentCount = parseInt(count.textContent) || 0;
        currentCount++;
        count.textContent = currentCount;
        count.style.display = 'flex';
        if (notification.comment_type == "Post") {
          const item = `
   <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">         
     <div class="preview-item-content">
    ${notification.commenter.name} Đã bình luận bài viết        
      <button style="margin:0;" class="btn post-notification" data-id="${notification.id}"  data-post-id="${notification.post_id}"><strong>${notification.post}</strong></button>
                    </div>
                </div>
   `;
          noficationList.insertAdjacentHTML("afterbegin", item);
          readPost();
        }
      }
      if (notification.comment_type == "Thread") {
        const item = `
   <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">
                   
     <div class="preview-item-content">
       ${notification.commenter.name} Đã bình luận bài viết  <button style="margin:0;" class="btn thread-notification"  data-thread-id="${notification.thread_id}" data-id="${notification.id}"><strong> ${notification.thread}</strong></button>
                    </div>
                </div>
   `;
        noficationList.insertAdjacentHTML("beforeend", item);
        readThread();
      }

      if (type === "follow.notification") {
        let currentCount = parseInt(count.textContent) || 0;
        currentCount++;
        count.textContent = currentCount;
        count.style.display = 'flex';
        const item = `
                <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">
                    <div class="preview-item-content">
                       <h6 class="preview-subject font-weight-normal">  
                      <strong> ${notification.friendship_user}</strong> đã gửi yêu cầu kết bạn
                                <button data-notification-id="${notification.id}"
                                    data-id="${notification.friendship_id}" class=" btn accept">Đồng ý</button>
                      </strong>
                       </h6>
                        <p class="font-weight-light small-text mb-0 text-muted">
                            ${notification.created_at}
                        </p>
                    </div>
                </div>
                
            `;
        followList.insertAdjacentHTML("afterbegin", item);
        acceptFriend();
      }
    });
</script>
<script>
  function acceptFriend() {
    let acceptBtn = document.querySelectorAll('.accept');
    if (acceptBtn) {
      acceptBtn.forEach(btn => {
        btn.addEventListener('click', function () {
          const friendId = this.dataset.id;
          const id = this.dataset.notificationId;
          $.ajax({
            url: `/api/nofication/follow-back/${friendId}`,
            method: 'POST',
            headers: {
              "Content-Type": "application/json",
              'Authorization': `Bearer ${auth_token}`
            },
            data: JSON.stringify({
              id: id
            }),
            success: function (response) {
              if (response.success === true) {
                count.textContent = parseInt(count.textContent) - 1;
              }
            },
            error: function (xhr, status, error) {
              console.error("Error: ", error);
            }
          });

        });
      });
    }
  }
  acceptFriend();

  function readPost() {
    let postBtn = document.querySelectorAll('.post-notification');
    postBtn.forEach(btn => {
      btn.addEventListener('click', function () {
        const id = this.dataset.id;
        let postId = this.dataset.postId;
        $.ajax({
          url: `mark-as-read-post/${id}`,
          method: 'GET',
          success: function (response) {
            if (response.success === true) {
              window.location.href = "/post/detail-post/" +
                postId;
              count.textContent = parseInt(count
                .textContent) - 1;
            }
          },
          error: function (xhr, status, error) {
            console.error("Error: ", error);
          }
        });
      });
    });
  }
  readPost();

  function readThread() {
    let threadBtn = document.querySelectorAll('.thread-notification');
    threadBtn.forEach(btn => {
      btn.addEventListener('click', function () {
        const threadId = this.dataset.threadId;
        const id = this.dataset.id;
        $.ajax({
          url: `mark-as-read-thread/${id}/${threadId}`,
          method: 'GET',
          success: function (response) {
            if (response.success === true) {

              if (response.type == 'status') {
                window.location.href = "/thread/detail/" + threadId;
                count.textContent = parseInt(count.textContent) - 1;
              } else {
                window.location.href = "/thread/topic/detail/" + threadId;
                count.textContent = parseInt(count.textContent) - 1;
              }
            }
          },
          error: function (xhr, status, error) {
            console.error("Error: ", error);
          }
        });
      });
    });
  }
  readThread();

  function followBack() {
    let followBackBtn = document.querySelectorAll('.read');
    if (followwBackBtn) {
      followwBackBtn.forEach(btn => {
        followwBackBtn.addEventListener('click', function () {
          const id = this.dataset.id;

          $.ajax({
            url: `/follow-back/mark-as-read-post/${id}`,
            method: 'GET',
            success: function (response) {
              if (response.success === true) {

                count.textContent = parseInt(count
                  .textContent) - 1;
              }
            },
            error: function (xhr, status, error) {
              console.error("Error: ", error);
            }
          });
        })
      })
    }
  }
</script>




<script>
  function notificationList() {
    document.getElementById('notification-list').addEventListener('click', function (e) {
      if (e.target && e.target.classList.contains('group-notification')) {
        e.preventDefault();
        const id = e.target.dataset.id;
        $.ajax({
          url: `/group/mark-as-read/${id}/${group_id}`,
          method: 'GET',
          headers: {
            "X-CSRF-TOKEN": '{{ csrf_token() }}',
          },
          success: function (response) {
            if (response.success == true) {
              window.location.href = "/group/find-result/" + group_id;
            }
          },
          error: function (xhr, status, error) {
            console.error("Error: ", error);
          }
        });
      }
      if (e.target && e.target.classList.contains('message-notification')) {
        e.preventDefault();
        const id = e.target.dataset.id;
        let senderId = e.target.dataset.senderId;
        $.ajax({
          url: `/mark-as-read/${id}`,
          method: 'GET',
          success: function (response) {
            if (response.success == true) {
              window.location.href = "/chat/" + userId + "/" + senderId;
            }
          }
        });
      }
      if (e.target && e.target.classList.contains('other-notification')) {
        e.preventDefault();
        const id = e.target.dataset.id;
        $.ajax({
          url: `/group/mark-as-read-other/${id}`,
          method: 'GET',
          headers: {
            "X-CSRF-TOKEN": '{{ csrf_token() }}',
          },
          success: function (response) {
            if (response.success == true) {
              window.location.href = "/group/" + userId;
            }
          },
          error: function (xhr, status, error) {
            console.error("Error: ", error);
          }
        });
      }
    });
  }
</script>

<script>
  document.getElementById("search-form").addEventListener("submit", function (e) {
    e.preventDefault();
    const searchInput = document.getElementById('navbar-search-input').value;

    $.ajax({
      url: '/search',
      method: 'POST',
      headers: {
        "X-CSRF-TOKEN": '{{ csrf_token() }}',
        "Content-Type": "application/json",
        'Authorization': `Bearer ${auth_token}`
      },
      data: JSON.stringify({
        search: searchInput
      }),
      success: function (response) {
        if (response.success == true) {
          if (response.type == 'user') {
            window.location.href = '/friend/search/' + response.id;
          }
          if (response.type == 'posts') {
            window.location.href = '/post/search-result?query=' + encodeURIComponent(
              searchInput);
          }
        }
      },
      error: function (xhr, status, error) {
        console.error("Error: ", error);
      }
    })
  })
</script>
<script>
  document.getElementById("logout").addEventListener("submit", function (e) {
    e.preventDefault();
    let auth_token = localStorage.getItem('auth_token');
    fetch('/logout', {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": '{{ csrf_token() }}',
        'Authorization': `Bearer ${auth_token}`
      },
    })
      .then(response => response.json())
      .then(data => {
        if (data.status == "success") {
          alert(data.message);
          localStorage.removeItem('user_id');
          localStorage.removeItem('auth_token');
          window.location.href = "/";
        } else {
          alert(data.message || "Đăng xuất thất bại!");
        }
      })
      .catch(error => console.error("Error:", error));
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('user_page').href = '/personal/personal-page/' + window.authUserId;

  });
</script>
<script>
  document.addEventListener('submit', function (e) {
    if (e.target && e.target.id === 'follow-back') {
      e.preventDefault();
      let auth_token = localStorage.getItem('auth_token');
      let userId = localStorage.getItem('user_id');
      let friendId = e.target.querySelector('#user_id').value;

      fetch(`/api/nofication/follow-back/${userId}`, {
        'method': "POST",
        'headers': {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${auth_token}`
        },
        body: JSON.stringify({
          friendId: friendId
        })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success === true) {
            alert(data.message);
          } else {
            alert("Có lỗi xảy ra!");
          }
        })
        .catch(error => {
          alert('Error: ' + error);
        });
    }
  });
</script>
<script>
  window.authUserId = @json(auth()->id());
  document.addEventListener('DOMContentLoaded', function () {
    let id = window.authUserId;
    let auth_token = localStorage.getItem('auth_token');
    fetch('/personal/load-avatar/' + id, {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${auth_token}`
      }
    }).then(response => response.json())
      .then(data => {
        if (data.success == true) {

          let avatarImg = document.querySelector('#profileDropdown img');
          if (avatarImg && data.user.avatar) {
            avatarImg.src = '{{ asset('storage/') }}/' + data.user.avatar;
          }
        } else {
          console.error('Failed to load avatar:', data.message);
        }
      })
  })
</script>