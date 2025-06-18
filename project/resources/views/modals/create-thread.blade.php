<div class="modal fade" id="createThreadModal" tabindex="-1" role="dialog" aria-labelledby="createShortPostModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createThread">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nội dung</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input name="content" id="content" type="text" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo</button>
                </div>
            </div>
        </form>
    </div>
</div>