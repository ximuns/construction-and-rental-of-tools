<div>
    @forelse($advantages as $advantage)
        <div class="advantages__card">
            <div class="advantages__cardIcon">
                <div style="--icon-url: url('{{ url('storage', $advantage->icon) }}')" class="advantages__icon" ></div>
            </div>
            <div>
                <p class='advantages__cardTitle'>
                    {{ $advantage->title }}
                </p>
                <p class='advantages__cardDescr'>
                    {{ $advantage->description }}
                </p>
            </div>
        </div>
    @empty
        <h1>Преимуществ нет</h1>
    @endforelse
</div>
