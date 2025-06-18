<div class="modal fade" id="editNameModal" tabindex="-1" role="dialog" aria-labelledby="editNameModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="updateName">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đổi tên người dùng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="new-name">Tên mới:</label>
                    <input name="new-name" id="new-name" type="text" class="form-control" value="{{ $user->name }}"
                        required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>