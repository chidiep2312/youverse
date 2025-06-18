@extends('admin.layout')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container py-4">
                <h2 class="mb-4">⚙️ Cài đặt hệ thống</h2>

                <form id="upload" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="support_email" class="form-label">Email hỗ trợ</label>
                        <input type="email" class="form-control" id="support_email" name="support_email"
                            value="{{ old('support_email', $setting->support_email ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="facebook" class="form-label">Facebook </label>
                        <input type="text" class="form-control" id="facebook" name="facebook"
                            value="{{ old('facebook', $setting->facebook ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="facebook" class="form-label">Hotline </label>
                        <input type="text" class="form-control" id="hotline" name="hotline"
                            value="{{ old('hotline', $setting->hotline ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="facebook" class="form-label">Mô tả </label>
                        <input type="text" class="form-control" id="des" name="des"
                            value="{{ old('des', $setting->des ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="banners" class="form-label">Ảnh banner 1400x400 (chọn 3)</label>
                        <input type="file" class="form-control" name="banners[]" id="banners" multiple accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('upload').addEventListener('submit', function (e) {
            e.preventDefault();
            const auth_token = localStorage.getItem('auth_token');
            const form = document.getElementById('upload');
            const formData = new FormData(form);

            $.ajax({
                url: `/api/admin/setting/update`,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {

                    'Authorization': `Bearer ${auth_token}`
                },
                success: function (response) {

                    if (response.status == true) {
                        alert(response.message);
                        location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error: ", error);
                }
            });
        })
    </script>
@endsection