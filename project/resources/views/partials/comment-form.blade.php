<form class="comment-form mt-2">
    @csrf
    <input type="hidden" class="thread_id" name="thread_id" value="{{ $thread->id }}">
    <div class="input-group shadow-sm">
        <input style="margin-right:5px;" type="text" name="content" class="form-control rounded-start content"
            placeholder="Viết bình luận..." required>
        <button class=" btn-secondary rounded-end" type="submit">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</form>