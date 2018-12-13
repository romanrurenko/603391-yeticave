<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $value): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?=$value['name']?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php $classname = count($errors) ? 'form--invalid' : '';?>
    <form class="form container   <?=$classname?>" action="login.php" method="post" enctype="multipart/form-data">
      <h2>Вход</h2>
        <?php
        $classname = isset($errors['email']) ? 'form__item--invalid' : '';
        $error = $errors['email'] ?? 'Введите e-mail';
        $value = $form['email'] ?? ''; ?>
      <div class="form__item  <?=$classname?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="login[email]" placeholder="Введите e-mail"  value="<?=$value?>" >
        <span class="form__error"><?=$error?></span>
      </div>
        <?php $classname = isset($errors['password']) ? 'form__item--invalid' : '';
        $value =  $errors['password'] ?? 'Введите пароль'; ?>
      <div class="form__item form__item--last <?=$classname?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="login[password]" placeholder="Введите пароль"  value="">
        <span class="form__error"><?=$value?></span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>

