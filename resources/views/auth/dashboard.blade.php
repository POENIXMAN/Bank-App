@extends('auth.layouts')

@section('content')

    @if (Session::get('success') || Session::get('error'))
        <div class="row justify-content-center mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard Message</div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @elseif ($message = Session::get('error'))
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('user.actions')
@endsection
