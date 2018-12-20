<nav class="nav">
    <ul class="nav__list container">
        <?php
        foreach ($categories as $value): ?>


        <?php $class_name = ($value['id'] === ($cur_filter ?? ''))?'nav__item--current':'';?>
            <li class="nav__item <?=$class_name?>">
                <a href="all-lots.php?filter=<?=$value['id']?>"><?= $value['name'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>