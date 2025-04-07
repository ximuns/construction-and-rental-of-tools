<header class="root container">
    <div class="root__header ">
        <div class="root__logo">
            <a href="/">СТРОЙЛАЙН</a>
        </div>
        <div class="root__menu">
            <menu class="root__list">
                <?php if ($_SERVER['REQUEST_URI'] === '/portfolio'): ?>
                <li class="root__link">
                    <a href="#">Главная</a>
                </li>
                <li class="root__link">
                    <a href="#">Аренда инструментов</a>
                </li>
                <li class="root__link">
                    <a href="#">Портфолио</a>
                </li>
                <?php else: ?>
                <li class="root__link">
                    <a href="#">Главная</a>
                </li>
                <li class="root__link">
                    <a href="#">Услуги</a>
                </li>
                <li class="root__link">
                    <a href="#">Калькулятор</a>
                </li>
                <li class="root__link">
                    <a href="#">Аренда инструментов</a>
                </li>
                <li class="root__link">
                    <a href="#">Контакты</a>
                </li>
                <?php endif; ?>
            </menu>
            <button class="root__button">Заказать звонок</button>
        </div>
    </div>
</header>
