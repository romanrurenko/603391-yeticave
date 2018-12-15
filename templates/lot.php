<section class="lot-item container">
    <h2><?= $lot['title'] ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['image_url'] ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['category'] ?></span></p>
            <p class="lot-item__description"><?= $lot['description'] ?></p>
        </div>
        <?php if (isset($_SESSION['user'])):?>
        <div class="lot-item__right">
        <div class="lot-item__state">
            <div class="lot-item__timer timer">
                10:54
            </div>
            <div class="lot-item__cost-state">
                <div class="lot-item__rate">
                    <?php $min_cost = $min_bid ?? '';
                      $value = $current_bid ?? '' ?>

                    <span class="lot-item__amount">Текущая цена</span>
                    <span class="lot-item__cost"><?=$value?></span>
                </div>
                <div class="lot-item__min-cost">
                    Мин. ставка <span><?=$min_cost?> р</span>
                </div>
            </div>
            <form class="lot-item__form" action="lot.php" method="post">
                <p class="lot-item__form-item form__item form__item--invalid">
                    <label for="cost">Ваша ставка</label>
                    <?php $value = $cost ?? '';?>
                    <input id="cost" type="text" name="cost" placeholder="<?=$min_cost?>" value="<?=$value?>">
                    <?php $value = $bid_errors['cost'] ?? ''?>
                    <span class="form__error"><?=$value?></span>
                </p>
                <?php $value = $lot['id'] ?? ''?>
                <input  class="visually-hidden" type="number" name="lot_id" value="<?=$value?>">
                <button type="submit" class="button">Сделать ставку</button>
            </form>
        </div>
            <?php endif;?>

       <?php
       if (count($bids)):?>
        <div class="history">

            <h3>История ставок (<span><?=count($bids)?></span>)</h3>


            <table class="history__list">

                <?php
                foreach ($bids as $value): ?>
                <?php $user_name = $value['name'];
                    $bid_amount = $value['amount'];
                    $time_after_bid = get_time_after_date($value['start_date'], time());
                ?>
                    <tr class="history__item">
                        <td class="history__name">
                            <?=$user_name?>
                        </td>
                        <td class="history__price">
                            <?=$bid_amount?>&nbsp;р
                        </td>
                        <td class="history__time">
                            <?=$time_after_bid?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
       <?php else: ?>
           Ставки по данному лоту отсутствуют.
       <?php endif;?>

    </div>
</section>