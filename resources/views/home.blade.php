@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fa fa-bar-chart"></i> Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Welcome {{ auth()->user()->name }}! You have <a href="/contacts">{{ auth()->user()->contacts_count }} {{ str_plural('contact', auth()->user()->contacts_count) }}.</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
