<div class="col-md-12 mb-4">
    <div class="card border-0 shadow-sm rounded overflow-hidden">
        <div class="row g-0">

            <div class="col-md-4">
                <img src="{{ $post->thumbnail ?? asset('assets/images/default.png') }}"
                    class="img-fluid h-100 w-100 object-fit-cover" alt="{{ $post->title }}">
            </div>

            <div class="col-md-8">
                <div class="p-3 d-flex flex-column h-100">


                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/' . $post->user->avatar) }}" class="rounded-circle me-3 shadow-sm"
                            style="width: 48px; height: 48px; object-fit: cover;">
                        <div style="margin-left:5px">
                            <div class="fw-semibold">
                                {{ $post->user->name ?? 'Unknown' }}
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i>
                                {{ $post->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>


                    <h5 class="fw-bold mb-2 text-dark">{{ $post->title }}</h5>


                    <p class="text-muted mb-3 flex-grow-1" style="font-size: 15px;">
                        {!!Illuminate\Support\Str::limit(strip_tags($post->des), 100) !!}
                    </p>


                    <div class="d-flex justify-content-between align-items-center mt-auto small text-muted">
                        <div>
                            <i class="fa-solid fa-eye me-1"></i> {{ $post->view_count }}
                            &nbsp;&nbsp;
                            <i class="fa-regular fa-heart me-1"></i>
                            {{ $post->likes->count() }}
                            &nbsp;&nbsp;
                            <i class="fa-regular fa-comment"></i> {{ $post->comments->count() }}
                        </div>
                        <a href="{{ route('post.detail', ['post' => $post->id]) }}"
                            class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>