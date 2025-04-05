<div>
    @forelse($rents as $rent)
        <x-rent-card :data="$rent" />
    @empty
        <h1>Инструмент нет</h1>
    @endforelse
</div>
