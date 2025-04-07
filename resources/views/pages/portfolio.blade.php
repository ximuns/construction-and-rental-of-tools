@extends('layout.app')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)

@section('content')
    <section class="portfolio">
        <div class="portfolio__container container">
            <livewire:portfolio />
        </div>
    </section>
@endsection
