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

<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= esc( $search ) ?></span>»</h2>
        <ul class="lots__list">

            <?php
            foreach ($ads as $key => $lot): ?>

                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $lot['image_url'] ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $lot['category'] ?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="#"><?= esc( $lot['title'] ) ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= format_price( esc( $lot['start_price'] ) ) ?></span>
                            </div>
                            <div class="lot__timer timer">
                                <?= esc(get_time_until_date_end(time(), $lot['date_end'])); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
    </ul>
</div>