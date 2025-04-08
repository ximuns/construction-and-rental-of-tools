<div class="footer__socials">
    @forelse($socialNetworks as $socialNetwork)
        <x-socialNetwork :data="$socialNetwork" />
    @empty
    @endforelse
</div>
