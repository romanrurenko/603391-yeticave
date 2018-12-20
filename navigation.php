<nav class="nav">
    <ul class="nav__list container">
        <?php
        foreach ($categories as $index): ?>
            <li class="nav__item">
                <a href="all-lots.php?filter=<?=$index['id']?>"><?= $index['name'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>