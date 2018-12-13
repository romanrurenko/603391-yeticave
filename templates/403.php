<nav class="nav">
    <ul class="nav__list container">
        <!--список из массива категорий-->
        <?php
        foreach ($categories as $index): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= $index['name'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>

</nav>

<section class="lot-item container">
    <h2>403 Доступ запрещен</h2>
    <p>Доступ к данной странице запрещен.</p>
</section>