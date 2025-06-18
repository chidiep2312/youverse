<div class="comment-list mb-3" id="comment-list-{{ $thread->id }}">
    @foreach ($thread->comments as $comment)
        <div class="comment-item d-flex justify-content-between align-items-center px-3 py-2 rounded">
            <div class="text-truncate">
                <strong class="me-1">{{ $comment->user->name }}</strong>
                <span class="text-muted small">{{ $comment->content }}</span>
            </div>
            @if (auth()->id() == $comment->user->id)
                <button class="btn delete-comment" value="{{ $comment->id }}" title="Xóa bình luận">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            @endif
        </div>
    @endforeach
</div>
<style>
    .comment-item {
        background-color: #f7f9fc;

        border-radius: 10px;
        transition: background-color 0.2s ease;
    }

    .comment-item:hover {
        background-color: #eef3f9;
    }

    .comment-item strong {
        color: #333;
    }

    .comment-item .text-muted {
        font-size: 0.875rem;
    }

    .delete-comment {
        background: none;
        border: none;
        padding: 0;
        line-height: 1;
    }

    .delete-comment i {
        font-size: 0.9rem;
    }
</style>