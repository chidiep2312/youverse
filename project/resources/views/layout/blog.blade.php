<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>YouVerse - Vũ trụ bạn viết</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.3/css/perfect-scrollbar.min.css">
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@0.1.2/css/themify-icons.css">
  <link rel="shortcut icon" href="{{ asset('assets/images/blog-logo.png') }}" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <div class="container-scroller">
    @include('components.navbar')
    <div style="margin:10px;" class="container-fluid page-body-wrapper">
      @include('components.sidebar')
      @yield('content')
      <div id="chat-notifications-list" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
      </div>
    </div>
  </div>
</body>
@include('components.footer')

<script>
  let receiverId = null;
  let id = null;
  let notificationCounts = {};
  window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
      console.log(notification);
      if (notification.type === 'message.notification') {
        const container = document.getElementById('chat-notifications-list');
        const senderId = notification.sender_id;
        if (notificationCounts[senderId]) {
          notificationCounts[senderId] += 1;
          const existing = document.querySelector(`#notification-${senderId}`);
          if (existing) {
            const countSpan = existing.querySelector('.message-count');
            countSpan.textContent = `x${notificationCounts[senderId]}`;
          }
        } else {
          notificationCounts[senderId] = 1;
          const newNotification = document.createElement('div');
          newNotification.classList.add('chat-notification', 'd-flex', 'align-items-center', 'mb-2');
          newNotification.id = `notification-${senderId}`;
          newNotification.style.background = '#fff';
          newNotification.style.padding = '10px';
          newNotification.style.border = '1px solid #ddd';
          newNotification.style.borderRadius = '5px';
          newNotification.style.cursor = 'pointer';

          newNotification.innerHTML = `
          <img src="/storage/${notification.sender_avatar}" alt="Avatar" 
            style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
          <div style="flex: 1;">
            <strong>${notification.sender_name}</strong> gửi  <span class="message-count" style="color: red; font-weight: bold;">1</span>tin nhắn mới 
          
          </div>
        `;

          newNotification.addEventListener('click', function () {
            $.ajax({
              url: `/mark-as-read/${notification.id}`,
              method: 'GET',
              success: function (response) {
                if (response.success == true) {
                  window.location.href = "/chat/" + userId + "/" + senderId;
                }
              }
            });
          });

          container.appendChild(newNotification);
        }

      }
    });
</script>

</html>