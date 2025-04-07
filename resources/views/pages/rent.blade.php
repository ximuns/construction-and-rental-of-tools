@extends('layout.app')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)

@section('content')
    <section class="rent @if(request()->is('rent')) rent__page @endif">
        <div class="rent__container container">
            <livewire:rent-page />
        </div>
    </section>
@endsection
