<?php $classname = isset( $errors['wrong_password'] ) ? 'form--invalid' : '';
$message = $errors['wrong_password'] ?? 'Вход'; ?>

<form class="form container   <?= $classname ?>" action="login.php" method="post" enctype="multipart/form-data">
    <h2><?=$message?></h2>
    <?php
    $classname = isset( $errors['email'] ) ? 'form__item--invalid' : '';
    $error = isset( $errors['email'] ) ? $errors['email'] : '';
    $value = $form['email'] ?? ''; ?>
    <div class="form__item  <?= $classname ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="login[email]" placeholder="Введите e-mail" value="<?= esc($value) ?>">
        <span class="form__error"><?= $error ?></span>
    </div>
    <?php $classname = isset( $errors['password'] ) ? 'form__item--invalid' : '';
    $value = $errors['password'] ?? ''; ?>
    <div class="form__item form__item--last <?= $classname ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="login[password]" placeholder="Введите пароль" value="">
        <span class="form__error"><?= esc($value) ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>

