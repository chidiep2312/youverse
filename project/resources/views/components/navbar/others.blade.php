<li class="nav-item dropdown">
    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
        <i class="fa-solid fa-bell"></i>
         @php
            $count = collect($notifications)->filter(function ($n) {
                return in_array($n->type, [
                    'App\Notifications\InviteNofication',
                    'App\Notifications\ApproveNotification',
                     'App\Notifications\leaveNotification',
                    'App\Notifications\RejectNotification',
                    'App\Notifications\RemoveNotification',
                    'App\Notifications\NewCommentNotification',
                    'App\Notifications\MessageNotification'
                ]);
            })->values();
        @endphp
        <span id="noticount" class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
            style="{{ $notifications->count() == 0 ? 'display:none;' : 'font-size:0.5rem;padding:0.3rem;' }}">
            {{   $count->count() }}
        </span>
    <div class=" dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
        <p class="mb-0 font-weight-normal dropdown-header"> <i class="ti-info-alt mx-0"></i>Thông báo</p>

        <div id="notification-list"   style="display: flex; flex-direction: column; gap: 10px; padding: 10px; max-height: 300px; overflow-y: auto;">

            @forelse ( $count as $notification)
                <div class="dropdown-item preview-item d-flex flex-column align-items-start">
                    <div class="preview-item-content">
                        @switch($notification->type)
                        
                            @case('App\Notifications\InviteNofication')
                                <a style="margin:0;" class=" btn group-notification" data-id="{{ $notification->id }}">
                                {{ $notification->data['group_creator'] }} mời bạn tham gia nhóm  <strong>    {{ $notification->data['group_name'] }}</strong> 
                                </a>
                            @break

                            @case('App\Notifications\MessageNotification')
                                <a style="margin:0;" class=" btn message-notification" data-sender-id="{{ $notification->data['sender_id'] }}" data-id="{{ $notification->id }}">
                                {{ $notification->data['sender_name'] }} gửi tin nhắn đến bạn 
                                </a>
                            @break
                            @case('App\Notifications\ApproveNotification')
                               
                                <a style="margin:0;" class=" btn other-notification " data-id="{{ $notification->id }}">
                              {{ $notification->data['group_creator'] }} Đồng ý yêu cầu tham gia     <strong>   {{ $notification->data['group_name'] }} </strong> của bạn
                                </a>
                            @break

                            @case('App\Notifications\RejectNotification')
                              
                                <a style="margin:0;"  class=" btn other-notification" data-id="{{ $notification->id }}">
                           {{ $notification->data['group_creator'] }} Từ chối yêu cầu tham gia  <strong> {{ $notification->data['group_name'] }} </strong>  của bạn
                                </a>
                            @break
                             @case('App\Notifications\leaveNotification')
                                <a style="margin:0;"  class=" btn other-notification" data-id="{{ $notification->id }}">
                                    {{ $notification->data['user_name'] }} Rời nhóm  <strong>  {{ $notification->data['group_name'] }}  </strong>của bạn</a>
                            @break

                            @case('App\Notifications\RemoveNotification')
                               
                                <a style="margin:0;" class=" btn other-notification" data-id="{{ $notification->id }}">
                                 {{ $notification->data['group_creator'] }} đã mời bạn khỏi nhóm <strong>  {{ $notification->data['group_name'] }} </strong> 
                                </a>
                            @break

                            @case('App\Notifications\NewCommentNotification')
                                
                                @if ($notification->data['comment_type'] == 'Post')
                               
                                    <button style="margin:0;"  class=" btn post-notification" data-post-id="{{$notification->data['post_id'] }}" data-id="{{ $notification->id }}">
                                    {{ $notification->data['commenter']['name'] }} đã bình luận bài viết    <strong> {!!Illuminate\Support\Str::limit(strip_tags($notification->data['post']), 20) !!}</strong> 
                                    </button>
                                @elseif ($notification->data['comment_type'] === 'Thread')
                                    <a  style="margin:0;" class="btn thread-notification" data-thread-id="{{$notification->data['thread_id'] }}" data-id="{{ $notification->id }}">
                                       {{ $notification->data['commenter']['name'] }} đã bình luận bài viết <strong>{!!Illuminate\Support\Str::limit(strip_tags($notification->data['thread']), 20) !!}</strong> 
                                    </a>
                                @endif
                            @break
                        @endswitch
                        <p class="font-weight-light small-text mb-0 text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                   
                @endforelse

            </div>
        </div>
    </li>
