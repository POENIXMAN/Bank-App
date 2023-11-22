@extends('auth.layouts')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard Message</div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            @if (session('user'))
                                Welcome, {{ session('user')['name'] }}! You are now logged in
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('main_menu')

@endsection
