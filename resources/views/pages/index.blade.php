@extends('layout.app')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)

@section('content')
    <section class="main" id="hero">
        <span class="main__cube_full"></span>
        <span class="main__cube_half"></span>
        <span class="main__bg_rounded"></span>
        <span class="main__bg_rounded"></span>
        <div class="main__container container">
            <div class="main__content">
                <h1 class="main__title"><span class="main__title-accent">Cтроительство</span> под ключ</h1>
                <p class="main__subtitle">Строительство малоэтажных домов, заборов, навесов, монтаж окон</p>
                <div class="main__buttons">
                    <a class="main__button" href="#calculator" >Рассчитать стоимость</a>
                    <a class="main__button" href="#service">Услуги</a>
                </div>
                <div class="main__advantages">
                    <livewire:advantage />
                </div>
            </div>
        </div>
    </section>
    <section class="services" id="service">
        <div class="services__container container">
            <div class="services__content">
                <div class="services__main">
                    <h1 class="services__title" >Наши услуги</h1>
                    <p class="services__subtitle" >Профессиональные строительные услуги для вашего дома и бизнеса</p>
                </div>
                <div class="services__cards">
                    <livewire:services />
                </div>

            </div>
        </div>
    </section>
    <livewire:calculator />
    <section class="rent" id="rent">
        <div class="rent__container container">
            <div class="rent__content">
                <div class="rent__main">
                    <h1 class="rent__title" >Аренда инструментов</h1>
                    <p class="rent__subtitle" >Широкий выбор профессиональных инструментов для ваших строительных проектов</p>
                </div>
                <div class="rent__cards">
                    <livewire:rent />
                </div>
                <a href="/rent" class="rent__cardLinks">
                    <p class="rent__cardLinkText">Смотреть весь каталог инструментов</p>
                    <img class="rent__arrow" src="{{ asset('assets/image/arrow.svg') }}" alt="стрелка">
                </a>
            </div>
        </div>
    </section>
    <section class="contact" id="contact">
        <div class="contact__container container">
            <div class="contact__content">
                <div class="contact__main">
                    <h1 class="contact__title" >Свяжитесь с нами</h1>
                    <p class="contact__subtitle" >У вас есть вопросы о наших услугах? Заполните форму, и наши специалисты свяжутся с вами в ближайшее время.</p>
                    <menu class="contact__links">
                      <livewire:contact />
                    </menu>
                </div>
                <livewire:feedback />
            </div>
        </div>
    </section>
@endsection
