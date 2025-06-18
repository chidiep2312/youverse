<div class="modal fade" id="uploadBgrModal" tabindex="-1" aria-labelledby="uploadBgrModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update-bgr" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadBgrModalLabel">Cập nhật ảnh bìa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="bgr" class="form-label">Chọn ảnh:</label>
                    <input type="file" name="bgr" id="bgr" accept="image/*" class="form-control" required>
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