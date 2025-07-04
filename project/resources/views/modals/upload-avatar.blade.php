<div class="modal fade" id="uploadAvatarModal" tabindex="-1" aria-labelledby="uploadAvatarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update-avatar" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadAvatarModalLabel">Cập nhật ảnh đại diện</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="avatar" class="form-label">Chọn ảnh:</label>
                    <input type="file" name="avatar" id="avatar" accept="image/*" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-upload"></i>
                        Cập
                        nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>