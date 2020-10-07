@extends('layouts.app')
@section('content')
    <post-component user_id="{{Auth::id()}}"></post-component>
@endsection
