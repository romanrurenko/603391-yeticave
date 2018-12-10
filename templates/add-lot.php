<nav class="nav">
    <ul class="nav__list container">
        <?php
        foreach ($categories as $value): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= $value['name'] ?></a>
            </li>
        <?php endforeach;?>
    </ul>
</nav>
    <?php $classname = count($errors) ? 'form--invalid' : '';?>
<form class="form form--add-lot container <?=$classname?>" action="add.php" method="post" enctype="multipart/form-data">
    <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
          <?php $classname = isset($errors['title']) ? 'form__item--invalid' : '';
          $value = $lot['title'] ?? ''; ?>
        <div class="form__item <?=$classname?>">
            <!-- form__item--invalid -->
          <label for="lot-name">Наименование</label>
          <input id="lot-name" type="text" name="lot[title]" placeholder="Введите наименование лота"
                 value="<?=$value?>" required><!--required-->
          <span class="form__error">Введите наименование лота</span>
        </div>
          <?php $classname = isset($errors['category']) ? 'form__item--invalid' : '';?>
        <div class="form__item">
          <label for="category">Категория</label>
          <select id="category" name="lot[category]" required><!--required-->
            <option value="0">Выберите категорию</option>

              <?php
              foreach ($categories as $index => $value):
               $attrib_name = (($lot['category'] ?? 0) === $value) ? 'selected' : '';?>
              <option  value="<?=$value['id']?>"<?=$attrib_name?>><?= $value['name']?></option>
              <?php endforeach; ?>

          </select>
          <span class="form__error">Выберите категорию</span>
        </div>
      </div>
    <?php $classname = isset($errors['description']) ? 'form__item--invalid' : '';
    $value = $lot['description'] ?? ''; ?>
      <div class="form__item form__item--wide <?=$classname?>">
        <label for="message">Описание</label>
        <textarea id="message" name="lot[description]" placeholder="Напишите описание лота"><?=$value?></textarea>
          <!--required-->
        <span class="form__error">Напишите описание лота</span>
      </div>
    <?php $classname = isset($lot['path']) ? 'form__item--uploaded ' : '';?>
      <div class="form__item form__item--file <?=$classname?>"> <!-- form__item--uploaded -->
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
      </div>
      <div class="form__container-three">
          <?php $classname = isset($errors['start_price']) ? 'form__item--invalid' : '';
          $value = $lot['start_price'] ?? ''; ?>
        <div class="form__item form__item--small <?=$classname?>">
          <label for="lot-rate">Начальная цена</label>
          <input id="lot-rate" type="number" name="lot[start_price]" placeholder="0"  value="<?= $value?>" required>
            <!--required-->
          <span class="form__error">Введите начальную цену</span>
        </div>
          <?php $classname = isset($errors['bid_step']) ? 'form__item--invalid' : '';
          $value = $lot['bid_step'] ?? '';?>
        <div class="form__item form__item--small <?=$classname?>">
          <label for="lot-step">Шаг ставки</label>
          <input id="lot-step" type="number" name="lot[bid_step]" placeholder="0"  value="<?= $value?>" required>
            <!--required-->
          <span class="form__error">Введите шаг ставки</span>
        </div>
          <?php $classname = isset($errors['date_end']) ? 'form__item--invalid' : '';
          $value = $lot['date_end'] ?? ''; ?>
        <div class="form__item <?=$classname?>">
          <label for="lot-date">Дата окончания торгов</label>
          <input class="form__input-date" id="lot-date" type="date" name="lot[date_end]"
                 value="<?=$value?>" required><!--required-->
          <span class="form__error">Введите дату завершения торгов</span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <ul>
        <?php
        if (count($errors)) {
            foreach ($errors as $err => $val): ?>
                <li><strong><?= $dict[$err] ?? ''; ?>:</strong> <?= $val; ?></li>
            <?php endforeach;
        }?>
    </ul>
      <button type="submit" class="button">Добавить лот</button>
    </form>