</section>
<section class="lots">
    <div class="lots__header">
        <h2>Лоты по категориям </h2>
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
                        <div class="lot__timer timer">
                            <?= esc(get_time_until_date_end( time(), $lot['date_end'] )); ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach;} ?>
    </ul>
</section>

<?php
if ($pages_count>1): ?>
<ul class="pagination-list">
    <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
    <li class="pagination-item pagination-item-active"><a>1</a></li>
    <li class="pagination-item"><a href="#">2</a></li>
    <li class="pagination-item"><a href="#">3</a></li>
    <li class="pagination-item"><a href="#">4</a></li>
    <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
</ul>
<?php endif?>