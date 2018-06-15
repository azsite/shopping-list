@extends('layouts.app')

@section('content')


    <form id="logout-form" action="/logout" method="POST" style="display: none;">
        {{ csrf_field() }}
        <input type="hidden" id="auth_check" volume="1">
    </form>

    <div id="app">

        <shopping-list></shopping-list>

    </div>

    <!-- Scripts -->
    <script src="{{ elixir('js/app.js') }}"></script>

@endsection
