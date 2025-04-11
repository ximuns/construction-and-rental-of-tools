<div class="portfolio__content">
    <div class="portfolio__main">
        <h1 class="portfolio__title">Наши проекты</h1>
        <p class="portfolio__subtitle">Ознакомьтесь с нашими реализованными проектами в различных направлениях строительства</p>
    </div>
    <div class="portfolio__tabs">
        <p class="portfolio__tab {{ is_null($activeCategory) ? 'active' : '' }}" wire:click="filterByCategory()">Все проекты</p>
        @foreach($categories as $category)
            <p class="portfolio__tab {{ $activeCategory == $category->id ? 'active' : '' }}"
               wire:click="filterByCategory({{ $category->id }})">{{ $category->title }}</p>
        @endforeach
    </div>
    <div class="portfolio__cards">
            @foreach($portfolios as $portfolio)
                <div class="portfolio__card" data-aos="fade-up">
                    <img class="portfolio__cardImg" src="{{ url('storage', $portfolio->image) }}" alt="{{ $portfolio->title }}">
                    <p class="portfolio__cardTitle">{{ $portfolio->title }}</p>
                    <p class="portfolio__cardText">{{ $portfolio->description }}</p>
                </div>
            @endforeach
    </div>
</div>
