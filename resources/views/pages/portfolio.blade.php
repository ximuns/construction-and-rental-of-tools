@extends('layout.app')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)

@section('content')
    <section class="portfolio">
        <div class="portfolio__container container">
            <div class="portfolio__content">
                <div class="portfolio__main">
                    <h1 class="portfolio__title">Наши проекты</h1>
                    <p class="portfolio__subtitle">Ознакомьтесь с нашими реализованными проектами в различных направлениях строительства</p>
                </div>
                <div class="portfolio__tabs">
                    <p class="portfolio__tab">Все проекты</p>
                    <p class="portfolio__tab">Дома</p>
                </div>
                <div class="portfolio__cards">
                    <div class="portfolio__card">
                        <img class="portfolio__cardImg" src="{{ asset('assets/image/portfolio/1.png') }}" alt="картинка">
                        <p class="portfolio__cardTitle">Дом в стиле модерн</p>
                        <p class="portfolio__cardText">Двухэтажный загородный дом площадью 180 кв.м. с панорамными окнами и открытой террасой. Использованы экологичные материалы и энергосберегающие технологии.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
