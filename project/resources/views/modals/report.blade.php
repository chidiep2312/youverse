<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="reportForm">
            @csrf
            <input type="hidden" name="thread_id" id="reportThreadId" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Tố cáo bài viết</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="reason">Chọn lý do tố cáo:</label>
                    <select class="form-control" name="reason" id="reason" required>
                        <option value="">-- Chọn lý do --</option>
                        <option value="Nội dung phản cảm">Nội dung phản cảm</option>
                        <option value="Spam hoặc quảng cáo">Spam hoặc quảng cáo</option>
                        <option value="Ngôn từ kích động/thù địch">Ngôn từ kích động/thù địch</option>
                        <option value="Thông tin sai sự thật">Thông tin sai sự thật</option>
                        <option value="Khác">Khác</option>
                    </select>
                    <label for="detail">Lý do tố cáo:</label>
                    <textarea class="form-control" name="detail" id="detail" rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Gửi tố cáo</button>
                </div>
            </div>
        </form>
    </div>
</div>