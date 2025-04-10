<div>
    @forelse($services as $service)
        <x-service-card :data="$service" />
    @empty
        <h1>Услуг нет</h1>
    @endforelse
        @if($showButton)
            <button wire:click="loadMore" class="services__button">
                Показать еще
            </button>
        @endif
</div>
