<main class="container">
    <section class="lots">
        <div class="lots__header">
            <h2>Мои ставки</h2>
        </div>
        <ul style="list-style: none; padding: 0px; padding-bottom: 60px;">
            <?php
            if (isset( $bids )) {
                foreach ($bids as $key => $bid): ?>

                    <?php $is_win_bid = ($bid['id'] === $bid['win_bid_id']);
                    $classname = ($is_win_bid)?'timer--finishing':'';
                    $contacts = $bid['contacts'];?>

                    <li class="history__item"  style="margin-bottom: 1px;">
                        <div>
                <span class="lot__title">
                <a class="text-link" href="lot.php?id=<?= esc( $bid['lot_id'] ) ?>">
                    <?= esc( $bid['lot_title'] ) ?>
                </a>
                </span>
                            <span class="lot-item__cost"  style="font-size: 16px;">
                    <?= format_price( esc( $bid['amount'] ) ) ?>
                </span>

                            <?php if ($is_win_bid): ?>
                                <div>
                                    <span style="color: #f84646; font-weight: bold;">Ваша ставка выиграла!</span>
                                    <span> Контакты продавца: <?=$contacts??''?></span>
                                </div>
                            <?php endif;?>

                        </div>

                        <span class="lot__timer timer <?=$classname?>">
                            <?= esc( $bid['start_date']??'' ); ?>



                    </li>
                <?php endforeach;
            } ?>
        </ul>
    </section>

    <?= $pagination ?? '' ?>
</main>
