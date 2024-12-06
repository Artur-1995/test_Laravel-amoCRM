@extends('layouts.app')

@section('title', Breadcrumbs::render('home'))

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <example-component></example-component>
        </div>
    </div>
@endsection
