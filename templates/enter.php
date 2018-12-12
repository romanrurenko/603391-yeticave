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


    <form class="form container" action="https://echo.htmlacademy.ru" method="post"> <!-- form--invalid -->
      <h2>Вход</h2>
      <div class="form__item"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" required>
        <span class="form__error">Введите e-mail</span>
      </div>
      <div class="form__item form__item--last">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль" required>
        <span class="form__error">Введите пароль</span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>