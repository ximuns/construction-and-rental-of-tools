@props(['data'])
<div class="main__advantages-item">
    <div class="main__advantages-title">
        <img src="{{ url('storage', $data->icon) }}" alt="иконка дома">
        <p class="main__advantages-titleText">{{ $data->title }}</p>
    </div>
    <p class="main__advantages-text">{{ $data->description }}</p>
</div>
