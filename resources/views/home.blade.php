@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="" method="post">
                        {{ csrf_field() }}
                        <input type="email" name="email" />
                        <button type="submit">Send invite</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
