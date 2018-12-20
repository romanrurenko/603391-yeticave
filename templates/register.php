<main>
<?= $navigation??'' ?>

<?php $classname = count( $errors ?? [] )  ? 'form--invalid' : ''; ?>

<form class="form container  <?= $classname ?>" action="register.php" method="post" enctype="multipart/form-data">
    <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>

    <?php $classname = isset( $errors['email'] ) ? 'form__item--invalid' : '';
    $value = $form['email'] ?? ''; ?>

    <div class="form__item <?= $classname ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="signup[email]"
               placeholder="Введите e-mail" value="<?= $value ?>" required>
        <?php $value = $errors['email'] ?? '';?>
        <span class="form__error"><?= $value ?></span>
    </div>

    <?php $classname = isset( $errors['password'] ) ? 'form__item--invalid' : '';
    $value = $form['password'] ?? ''; ?>

    <div class="form__item <?= $classname ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="signup[password]"
               placeholder="Введите пароль" value="<?= $value ?>" required>
        <?php $value = $errors['password'] ?? '';?>
        <span class="form__error"><?= $value ?></span>
    </div>

    <?php $classname = isset( $errors['name'] ) ? 'form__item--invalid' : '';
    $value = $form['name'] ?? ''; ?>

    <div class="form__item <?= $classname ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="signup[name]" placeholder="Введите имя"
               value="<?= $value ?>" required>
        <?php $value = $errors['name'] ?? '';?>
        <span class="form__error"><?= $value ?></span>
    </div>

    <?php $classname = isset( $errors['contacts'] ) ? 'form__item--invalid' : '';
    $value = $form['contacts'] ?? ''; ?>

    <div class="form__item <?= $classname ?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="signup[contacts]"
                  placeholder="Напишите как с вами связаться" required><?= esc($value) ?></textarea>
        <?php $value = $errors['contacts'] ?? '';?>
        <span class="form__error"><?= $value ?></span>
    </div>

    <?php $classname = isset( $errors['path'] ) ? 'form__item--invalid' : ''; ?>

    <div class="form__item form__item--file form__item--last <?= $classname ?>">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="file" id="photo2" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>

        </div>
        <?php $value = $errors['path'] ?? '';?>
        <span class="form__error"><?= $value ?></span>
    </div>


    <?php
    if (($errors)) {
        ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php } ?>


    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
</main>