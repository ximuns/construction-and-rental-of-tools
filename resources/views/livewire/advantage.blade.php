<div>
    @forelse($advantages as $advantage)
        <x-advantage-card :data="$advantage" />
    @empty
        <h1>Преимуществ нет</h1>
    @endforelse
</div>
