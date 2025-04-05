<div>
    @forelse($services as $service)
        <x-service-card :data="$service" />
    @empty
        <h1>Услуг нет</h1>
    @endforelse
</div>
