@extends('layouts.book-layout')

@section('title', 'Create Book')

@section('content')
    @include('components.book-modal')
    @include('components.book-table')
@endsection
