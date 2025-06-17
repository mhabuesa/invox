@extends('errors::minimal')

@section('title', __('Permission Denied'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('button')
    <a href="{{ url()->previous() }}" class="text-gray-500 btn btn-primary">
        {{ __('Go Back') }}
    </a>
@endsection
