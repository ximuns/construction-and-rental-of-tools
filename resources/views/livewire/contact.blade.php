<div>
    @forelse($contacts as $contact)
        <x-contact :data="$contact" />
    @empty
        <h1>Контактов нет</h1>
    @endforelse
</div>
