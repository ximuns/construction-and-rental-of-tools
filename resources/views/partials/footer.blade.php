<footer class="footer @if(request()->is('portfolio') || request()->is('rent')) footer__notMain @endif">
    <div class="footer__container container">
        <div class="footer__content">
            <div class="footer__top">
                <div class="footer__logo">
                    <a class="footer__logoLink" href="/">СТРОЙЛАЙН</a>
                </div>
                <menu class="footer__links">
                    <?php if ($_SERVER['REQUEST_URI'] === '/portfolio' || $_SERVER['REQUEST_URI'] === '/rent'): ?>
                    <li class="footer__link">
                        <a class="footer__linkText" href="/">Главная</a>
                    </li>
                    <li class="footer__link">
                        <a class="footer__linkText" href="/rent">Аренда инструментов</a>
                    </li>
                    <li class="footer__link">
                        <a class="footer__linkText" href="/portfolio">Портфолио</a>
                    </li>
                    <?php else: ?>
                    <li class="footer__link">
                        <a class="footer__linkText" href="/">Главная</a>
                    </li>
                    <li class="footer__link">
                        <a class="footer__linkText" href="#service">Услуги</a>
                    </li>
                    <li class="footer__link">
                        <a class="footer__linkText" href="#calculator">Калькулятор</a>
                    </li>
                    <li class="footer__link">
                        <a class="footer__linkText" href="/rent">Аренда инструментов</a>
                    </li>
                    <li class="footer__link">
                        <a class="footer__linkText" href="#contact">Контакты</a>
                    </li>
                    <?php endif; ?>
                </menu>
            </div>
            <div class="footer__bottom">
                <p class="footer__text">
                    Надежный партнер в строительстве. Мы предлагаем полный спектр строительных услуг с гарантией качества.
                </p>
                    <livewire:social-network />
            </div>
        </div>
    </div>
</footer>
