@props(['data'])
@foreach($data->social_networks as $socialNetwork)
    <li class="footer__social">
        <a class="footer__socialLink" href="{{ $socialNetwork['link'] }}">
            <img class="footer__icon" src="{{ url('storage', $socialNetwork['icon']) }}" alt="{{ $socialNetwork['name'] ?? '' }}">
        </a>
    </li>
@endforeach
