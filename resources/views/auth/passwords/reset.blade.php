@extends('layouts.master')

{{-- page title --}}
@section('title', 'reset password form')

{{-- page description --}}
@section('description', '')

{{-- page keywords --}}
@section('keywords', '')

{{-- og type --}}
@section('ogType', 'website')

{{-- page content --}}
@section('content')

    <reset-password-component str-token="{{ $token }}" str-email="{{ $email }}"></reset-password-component>

@endsection
