<main class="container">
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
        снаряжение.</p>
    <ul class="promo__list">
        <!--список из массива категорий-->
        <?php
        foreach ($categories as $index): ?>
            <li class="promo__item promo__item--<?= $index['style_name'] ?>">
                <a class="promo__link" href="all-lots.php?filter=<?=$index['id']?>"><?= $index['name'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php
        if (isset($ads)) {foreach ($ads as $key => $lot): ?>

            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $lot['image_url'] ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= $lot['category'] ?></span>
                    <h3 class="lot__title">
                        <a class="text-link" href="lot.php?id=<?= esc( $lot['id'] ) ?>"><?= esc( $lot['title'] ) ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= format_price( esc( $lot['start_price'] ) ) ?></span>
                        </div>

                        <?php $class_name = time_is_finish( time(), $lot['date_end']??'' );?>
                        <div class="lot__timer timer <?=$class_name?>">
                            <?= esc(get_time_until_date_end( time(), $lot['date_end'] )); ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach;} ?>
    </ul>
</section>

<?=$pagination ?? ''?>
    </main>
