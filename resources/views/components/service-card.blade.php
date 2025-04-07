@props(['data'])
<div class="services__card">
    <img class="services__cardImg" src="{{ url('storage', $data->image) }}" alt="Картинка услуги">
    <div class="services__cardName">
        <img class="services__icon" src="{{ url('storage', $data->icon) }}" alt="иконка">
        <p class="services__name">{{ $data->title }}</p>
    </div>
    <p class="services__cardText">{{ $data->description }}</p>
    <a href="/portfolio" class="services__cardLink">
        <p class="services__cardLinkText">Примеры работ</p>
        <img class="services__arrow" src="{{ asset('assets/image/arrow.svg') }}" alt="стрелка">
    </a>
</div>
