<main>
    <?= $navigation ?? '' ?>
<div class="container">
    <section class="lots">
        <h2><?=$block_title ?? ''?>«<span><?= esc( $search ) ?></span>»</h2>
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
                            <a class="text-link" href="lot.php?id=<?=$lot['id']?>"><?= esc( $lot['title'] ) ?></a>
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
</div>

</main>