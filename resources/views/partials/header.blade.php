<header class="root container">
    <div class="root__header ">
        <div class="root__logo">
            <a href="/">СТРОЙЛАЙН</a>
        </div>
        <button class="burger" aria-label="Меню">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="root__menu">
            <menu class="root__list">
                <?php if ($_SERVER['REQUEST_URI'] === '/portfolio' || $_SERVER['REQUEST_URI'] === '/rent'): ?>
                <li class="root__link">
                    <a href="/">Главная</a>
                </li>
                <li class="root__link">
                    <a href="/rent">Аренда инструментов</a>
                </li>
                <li class="root__link">
                    <a href="/portfolio">Портфолио</a>
                </li>
                <?php else: ?>
                <li class="root__link">
                    <a href="/">Главная</a>
                </li>
                <li class="root__link">
                    <a href="#service">Услуги</a>
                </li>
                <li class="root__link">
                    <a href="#calculator">Калькулятор</a>
                </li>
                <li class="root__link">
                    <a href="/rent">Аренда инструментов</a>
                </li>
                <li class="root__link">
                    <a href="#contact">Контакты</a>
                </li>
                <?php endif; ?>
            </menu>
            <?php if ($_SERVER['REQUEST_URI'] === '/portfolio' || $_SERVER['REQUEST_URI'] === '/rent'): ?>
                <a href="/#contact" class="root__button">Заказать звонок</a>
            <?php else: ?>
                <a href="#contact" class="root__button">Заказать звонок</a>
            <?php endif; ?>
        </div>
    </div>
</header>
