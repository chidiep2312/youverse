<div class="modal fade" id="createTagModal" tabindex="-1" role="dialog" aria-labelledby="changeSlogan"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="create-tag">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo thẻ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên thẻ</label>
                        <input id="tag_name" name="tag_name" type="text" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo</button>
                </div>
            </div>
        </form>
    </div>
</div>