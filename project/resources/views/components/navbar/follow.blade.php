<li class="nav-item dropdown">
    <a class="nav-link count-indicator dropdown-toggle" id="followDropdown" href="#" data-toggle="dropdown">
        <i class="mdi mdi-account"></i>
        @php
            $count = collect($notifications)->filter(function ($n) {
                return in_array($n->type, [
                    'App\Notifications\FollowNotification',
                    'App\Notifications\FollowBackNotification'
                ]);
            })->values();
        @endphp
        <span id="count" class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
            style="{{ $notifications->count() == 0 ? 'display:none;' : 'font-size:0.5rem; padding:0.3rem;' }}">
            {{  $count->count() }}
        </span>

    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="followDropdown">
        <p class="mb-0 font-weight-normal dropdown-header">
            <i class="ti-user mx-0"></i>Thông báo
        </p>
        <div id="follow-list"
            style="display: flex; flex-direction: column; gap: 10px; padding: 10px; max-height: 300px; overflow-y: auto;">
            @forelse ($count as $notification)

                <div class="dropdown-item preview-item"
                    style="display: flex; flex-direction: column; align-items: flex-start;">
                    <div class="preview-item-content">
                        <h6 class="preview-subject font-weight-normal">
                            @if ($notification->type == 'App\Notifications\FollowNotification' && isset($notification->data['friendship_user']))
                                <strong>{{ $notification->data['friendship_user'] }}</strong> đã gửi yêu cầu kết bạn
                                <button data-notification-id="{{ $notification->data['id'] }}"
                                    data-id="{{$notification->data['friendship_id']}}" class=" btn accept">Đồng ý</button>

                            @endif

                            @if ($notification->type == 'App\Notifications\FollowBackNotification' && isset($notification->data['friendship_friend']))
                                <a class="read" href="#" data-id="{{ $notification->data['id'] }}">
                                    <strong>{{ $notification->data['friendship_friend'] }}</strong> đã đồng ý kết bạn
                                </a>

                            @endif
                        </h6>
                        <p class=" font-weight-light small-text mb-0 text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty

            @endforelse
        </div>
    </div>
</li>