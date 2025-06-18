<div class="col-md-6 col-lg-4 mb-4">
    <div class="blog-card-modern shadow-sm rounded overflow-hidden h-100">
        <div class="blog-thumbnail">
            <img src="{{ $post->thumbnail ?? asset('assets/images/default.png') }}" alt="{{ $post->title }}">
        </div>
        <div class="blog-body p-3 d-flex flex-column shadow-sm rounded border bg-white h-100">

            <div class="d-flex align-items-center mb-3">
                <img src="{{ asset('storage/' . $post->user->avatar) }}" class="rounded-circle me-3 shadow-sm"
                    style="width: 48px; height: 48px; object-fit: cover;">
                <div style="margin-left:5px">
                    <div class="fw-semibold"><a
                            href="{{ route('friend.page', ['user' => $post->user->id]) }}">{{ $post->user->name }}</a>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-clock"></i> {{ $post->created_at->format('d M Y') }}
                    </small>
                </div>
            </div>
            <h5 class="fw-bold mb-2 text-dark">{!!Illuminate\Support\Str::limit(strip_tags($post->title), 25) !!}</h5>

            <p class="text-muted flex-grow-1" style="font-size: 15px;">
                {!!Illuminate\Support\Str::limit(strip_tags($post->des), 50) !!}
            </p>
            <div class="d-flex justify-content-between align-items-center small text-muted mt-auto">
                <div>
                    <i class="fa-solid fa-eye me-1"></i> {{ $post->view_count }}
                    &nbsp;&nbsp;
                    <i class="fa-regular fa-heart me-1"></i> {{ $post->likes->count() }}
                    &nbsp;&nbsp;
                    <i class="fa-regular fa-comment"></i> {{ $post->comments->count() }}
                </div>

                @if (\Illuminate\Support\Facades\Route::currentRouteName() !== 'welcome')
                    <a href="{{ route('post.detail', ['post' => $post->id]) }}" class="btn btn-sm btn-outline-primary">
                        Xem chi tiết
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>