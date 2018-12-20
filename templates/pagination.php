<?php
if ($pages_count>1): ?>
<ul class="pagination-list">

    <?php if ($cur_page>1) {
        $value=($page_name ?? ''). '?page='.((int)$cur_page-1). '&filter='. $cur_filter??'';
    } else {
        $value='#';
    }?>



    <li class="pagination-item pagination-item-prev"><a href="<?=$value;?>">Назад</a></li>


    <?php foreach ($pages as $page): ?>
        <li class="pagination-item <?php if ((int)$page === (int)$cur_page): ?>pagination-item-active<?php endif; ?>">



            <?php $value = '&filter='. ($cur_filter ?? '' )?>
            <a href="<?=$page_name;?>?page=<?=$page;?><?=$value?>"><?=$page;?></a>
        </li>
    <?php endforeach; ?>

    <?php if ($cur_page<$pages_count) {
        $value=($page_name ?? '').'?page='.((int)$cur_page+1). '&filter='. $cur_filter??'';
    } else {
        $value='#';
    }?>

        <li class="pagination-item pagination-item-next"><a href="<?=$value;?>">Вперед</a></li>


</ul>
<?php endif?>
