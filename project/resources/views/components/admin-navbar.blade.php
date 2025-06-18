<link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
<style>
</style>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href=""><img id="logo" src="{{ asset('assets/images/blog-logo.png')}}"
                class="mr-2" alt="logo" /> <span class="ml-2 font-weight-bold text-dark">ADMIN</span></a>

        <a class="navbar-brand brand-logo-mini" href=""><img src="{{ asset('assets/images/blog-logo.png')}}"
                alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

        <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-search d-none d-lg-block">
                <div class="input-group">
                    <a id="setPass" class="dropdown-item" href="#">
                        <button type="button" style="background-color: transparent; border: none;">
                            <i class="mdi mdi-key"></i> Đặt lại mật khẩu
                        </button>
                    </a>

                </div>
            </li>
            <form id="logout">
                @csrf
                <span class="dropdown-item">
                    <button type="submit" style="background-color: transparent; border: none;">
                        <i class="ti-power-off text-primary"></i>
                        Đăng xuất
                    </button>
                </span>

            </form>
            </li>
        </ul>

    </div>
</nav>
@include('modals.create-announce')
<script>
    document.getElementById('setPass').addEventListener('click', function (e) {
        e.preventDefault();
        window.location.href = '/set-password';

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
                    alert(data.message || "Logout false!");
                }
            })
            .catch(error => console.error("Error:", error));
    });
</script>
<script>
    document.getElementById('create-announce').addEventListener('submit', function (e) {
        e.preventDefault();
        let content = document.getElementById('content').value;
        let title = document.getElementById('title').value;
        let auth_token = localStorage.getItem('auth_token');
        console.log("Token: ", auth_token);
        $.ajax({
            url: `/api/admin/annoucement/save`,
            method: "POST",
            data: {
                title: title,
                content: content,
            },
            headers: {
                'Authorization': `Bearer ${auth_token}`
            },
            success: function (response) {

                if (response.success == true) {
                    alert(response.message);
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                console.error("Error: ", error);
            }
        });

    });
</script>