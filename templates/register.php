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

<?php $classname = count( $errors ) ? 'form--invalid' : ''; ?>

<form class="form container  <?= $classname ?>" action="register.php" method="post" enctype="multipart/form-data">
    <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>

    <?php $classname = isset( $errors['email'] ) ? 'form__item--invalid' : '';
    $value = $form['email'] ?? ''; ?>

    <div class="form__item <?= $classname ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="signup[email]"
               placeholder="Введите e-mail" value="<?= $value ?>" required>
        <span class="form__error">Введите e-mail</span>
    </div>

    <?php $classname = isset( $errors['password'] ) ? 'form__item--invalid' : '';
    $value = $form['password'] ?? ''; ?>

    <div class="form__item <?= $classname ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="signup[password]"
               placeholder="Введите пароль" value="<?= $value ?>" required>
        <span class="form__error">Введите пароль</span>
    </div>

    <?php $classname = isset( $errors['name'] ) ? 'form__item--invalid' : '';
    $value = $form['name'] ?? ''; ?>

    <div class="form__item <?= $classname ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="signup[name]" placeholder="Введите имя"
               value="<?= $value ?>" required>
        <span class="form__error">Введите имя</span>
    </div>

    <?php $classname = isset( $errors['contacts'] ) ? 'form__item--invalid' : '';
    $value = $form['contacts'] ?? ''; ?>

    <div class="form__item <?= $classname ?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="signup[contacts]"
                  placeholder="Напишите как с вами связаться" required><?= esc($value) ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>

    <?php $classname = isset( $file['path'] ) ? 'form__item--uploaded ' : ''; ?>

    <div class="form__item form__item--file form__item--last <?= $classname ?>">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="file" id="photo2" value="" required>
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>


    <?php
    if (($errors)) {
        ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <ul>
            <?php foreach ($errors as $error => $value): ?>
                <li><strong><?= $dict[$error] ?? ''; ?>:</strong> <?= esc($value); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php } ?>


    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
