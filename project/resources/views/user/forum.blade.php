@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forum.css') }}">
    <div class="main-panel " style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container-fluid px-3">
                @if (isset($announcements))
                    <div class="container-fluid px-2">
                        @foreach ($announcements as $a)
                            <div class="alert alert-warning d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-2"
                                role="alert" style="border-radius: 15px;">
                                <div class="flex-grow-1">
                                    <strong>📢 {{ $a->title }}:</strong> {{ $a->content }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="discussion-header">
                    <h2>Thảo luận cùng cộng đồng</h2>
                    <p>Khám phá, tìm kiếm và bắt đầu những chủ đề thú vị cùng mọi người.</p>
                    <form>
                        <div class="discussion-tools">
                            <input name="title" type="text" placeholder="Tìm kiếm chủ đề...">
                            <select name="tag_id" class="tag-filter">
                                <option value="">Chủ đề</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->tag_name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="status">
                                <option value="latest"> Mới nhất</option>
                                <option value="popular"> Nổi bật</option>

                            </select>
                            <button type="submit" class="btn btn-outline-primary">Tìm</button>
                            <a href="{{ route('thread.create-topic') }}" class="btn-create-thread">+ Tạo chủ đề mới</a>
                        </div>
                    </form>
                </div>

                <div class="forum-container">
                    <div class="thread-list">

                        @foreach ($topics as $t)
                            <div class="thread">
                                <div class="avatar"
                                    style="background-image: url('{{ asset('storage/' . $t->user->avatar) }}');">

                                </div>
                                <div class="thread-content">
                                    <div class="thread-title"> <a
                                            href="{{ route('thread.detail-topic', ['topic' => $t->id]) }}">{!! $t->title !!}</a>
                                    </div>
                                    <div class="thread-meta">
                                        <span class="tag">{{ $t->tag->tag_name ?? '' }}</span>
                                        <span>bởi {{ $t->user->name }} • {{ $t->created_at->diffForHumans() }}</span>

                                        <span><i class="fa-regular fa-comment"></i>{{ $t->comments->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="sidebar">
                        <div class="ranking-box">
                            <h3>Không nên bỏ qua</h3>
                            @if (isset($pinned))
                                @foreach ($pinned as $index => $p)
                                    <div class="ranking-item"><span>{{ $index + 1 }}. <a
                                                href="{{ route('thread.detail-topic', ['topic' => $p->id]) }}">{!! $p->title !!}</a></span><span>+{{ $p->comments->count() }}</span>
                                    </div>
                                @endforeach
                            @endif

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>



@endsection