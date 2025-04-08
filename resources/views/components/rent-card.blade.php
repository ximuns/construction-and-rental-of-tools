@props(['data'])
<div class="rent__card">
    <img class="rent__cardImg" src="{{ url('storage', $data->image) }}" alt="Картинка услуги">
    <div class="rent__cardInfo">
        <div class="rent__cardName">
            <p class="rent__name">{{ $data->title }}</p>
            <p class="rent__availability {{ $data->is_access ? '' : 'active' }}">
                {{ $data->is_access ? 'Доступно' : 'Занято' }}
            </p>
        </div>
        <p class="rent__cardText">{{ $data->description }}</p>
        <div class="rent__cardPrice">
            <p class="rent__price">{{ $data->price }}₽/день</p>
            @if($data->is_access)
                <button wire:click="openModal({{ $data->id }})" class="rent__cardLink">Забронировать</button>
            @endif
        </div>
    </div>
</div>
