<link rel="stylesheet" href="{{ asset('assets/css/comment.css') }}">
<div class="comments">
    @if($comments->isEmpty())
        <h4>Chưa có bình luận nào!</h4>
    @else
        @foreach($comments as $c)
            <div class="comment-box d-flex mb-3">
                <img src="{{ asset('storage/' . $c->user->avatar) }}" class="rounded-circle me-3" alt="avatar"
                    style="width: 50px; height: 50px;">

                <div class="flex-grow-1 bg-light p-3 rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark">{{ $c->user->name }}</strong>
                        <small class="text-muted">
                            {{ $c->created_at->diffForHumans() }}
                            @if(auth()->id() == $c->user->id)
                                <button class="delete-comment border-0 bg-transparent ms-2" value="{{ $c->id }}">
                                    <i class="fa-solid fa-trash-can text-secondary" style="font-size: 1.2rem;"></i>
                                </button>
                            @endif
                        </small>
                    </div>
                    <div class="text-body">
                        {{ $c->content }}
                    </div>
                </div>
            </div>

        @endforeach
        @if ($comments->hasMorePages())
            <div class="load-more-comments text-center mt-3">
                <button id="loadMoreComments" class="btn " data-next-page="{{ $comments->currentPage() + 1 }}">
                    Tải thêm bình luận
                </button>
            </div>
        @endif
    @endif
</div>