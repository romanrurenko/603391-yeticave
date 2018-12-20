<main>
<?php $classname = (isset($errors) ) ? 'form--invalid' : ''; ?>
<form class="form form--add-lot container <?= $classname ?>" action="add.php" method="post"
      enctype="multipart/form-data">
    <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $classname = isset( $errors['title'] ) ? 'form__item--invalid' : '';
        $value = $lot['title'] ?? ''; ?>
        <div class="form__item <?= $classname ?>">
            <!-- form__item--invalid -->
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot[title]" placeholder="Введите наименование лота"
                   value="<?= esc($value) ?>" required><!--required-->
            <span class="form__error"><?= $value ?></span>
        </div>
        <?php $classname = isset( $errors['category'] ) ? 'form__item--invalid' : ''; ?>
        <div class="form__item <?= $classname ?>">
            <label for="category">Категория</label>
            <select id="category" name="lot[category]" required><!--required-->
                <option value="0">Выберите категорию</option>

                <?php
                foreach ($categories as $value):
                    if ((int)($lot['category'] ?? 0) === $value['id']) {
                        $attrib_name = 'selected';
                    } else {
                        $attrib_name = '';
                    } ?>
                    <option value="<?= $value['id'] ?>"<?= $attrib_name ?>><?= $value['name'] ?></option>
                <?php endforeach; ?>

            </select>
            <?php $value = $errors['category'] ?? '';?>
            <span class="form__error"><?= $value ?></span>
        </div>
    </div>
    <?php $classname = isset( $errors['description'] ) ? 'form__item--invalid' : '';
    $value = $lot['description'] ?? ''; ?>
    <div class="form__item form__item--wide <?= $classname ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="lot[description]" placeholder="Напишите описание лота" required><?= esc($value) ?></textarea>
        <!--required-->
        <?php $value = $errors['description'] ?? '';?>
        <span class="form__error"><?= $value ?></span>
    </div>
    <?php $classname = isset( $errors['path'] ) ? 'form__item--invalid ' : ''; ?>
    <div class="form__item form__item--file <?= $classname ?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" name="lot-image" type="file" id="photo2" value="" required>
            <label for="photo2">
                <span>+ Добавить</span>
            </label>

        </div>
        <?php $value = $errors['path'] ?? '';?>
        <span class="form__error"><?= $value ?></span>
    </div>
    <div class="form__container-three">
        <?php $classname = isset( $errors['start_price'] ) ? 'form__item--invalid' : '';
        $value = $lot['start_price'] ?? ''; ?>
        <div class="form__item form__item--small <?= $classname ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot[start_price]" placeholder="0" value="<?= esc($value) ?>" required>
            <!--required-->
            <?php $value = $errors['start_price'] ?? '';?>
            <span class="form__error"><?= $value ?></span>
        </div>
        <?php $classname = isset( $errors['bid_step'] ) ? 'form__item--invalid' : '';
        $value = $lot['bid_step'] ?? ''; ?>
        <div class="form__item form__item--small <?= $classname ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot[bid_step]" placeholder="0" value="<?= esc($value) ?>" required>
            <!--required-->
            <?php $value = $errors['bid_step'] ?? '';?>
            <span class="form__error"><?= $value ?></span>
        </div>
        <?php $classname = isset( $errors['date_end'] ) ? 'form__item--invalid' : '';
        $value = $lot['date_end'] ?? ''; ?>
        <div class="form__item <?= $classname ?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" name="lot[date_end]"
                   value="<?= esc($value) ?>" required><!--required-->
            <?php $value = $errors['date_end'] ?? '';?>
            <span class="form__error"><?= $value ?></span>
        </div>
    </div>

    <?php
    if (isset( $errors )) {
        ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php } ?>

    <button type="submit" class="button">Добавить лот</button>
</form>
</main>