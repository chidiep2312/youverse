@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-page.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper bg-blur">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card profile-card">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="User Avatar"
                                        class="rounded-circle profile-avatar">
                                </div>
                                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                                <p class="text-muted small mb-3">Id:{{ $user->id }}</p>

                                <div class="profile-info text-start px-4">
                                    <p><strong>Email:</strong> {{ $user->email }}</p>
                                    <p><strong>Tham gia:</strong>
                                        {{ $user->created_at->format('d/m/Y') }}</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection