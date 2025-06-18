<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat-channel.{user1_id}.{user2_id}', function ($user, $user1_id, $user2_id) {
    return (int) $user->id === (int) $user1_id || (int) $user->id === (int) $user2_id;
});