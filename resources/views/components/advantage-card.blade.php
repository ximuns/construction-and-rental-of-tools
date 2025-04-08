@props(['data'])
<div class="main__advantages-item">
    <div class="main__advantages-title">
        <div class="main__icon" style="--icon-url: url('{{ url('storage', $data->image) }}')"></div>
        <p class="main__advantages-titleText">{{ $data->title }}</p>
    </div>
    <p class="main__advantages-text">{{ $data->description }}</p>
</div>
