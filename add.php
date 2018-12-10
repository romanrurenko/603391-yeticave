<?php
require_once('data.php');
require_once('functions.php');
require_once('config.php');

// загружаем категории
if (!$link) {
    $db_error = mysqli_connect_error();
    show_error($page_content, $db_error);
} else {
    $sql = 'SELECT `id`, `name`, `style_name` FROM categories ORDER BY `id`';
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        show_error($page_content, $error);
    }
}

// есть ли запрос
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lot'])) {
    $lot = $_POST['lot'];
    $required = ['title', 'description', 'start_price', 'bid_step',
        'category', 'date_end'];
    $dict = ['title' => 'Название', 'description' => 'Описание', 'path' => 'Изображение',
        'start_price' => 'Начальная цена', 'bid_step' => 'Шаг ставки', 'category' => 'Категория',
        'date_end' => 'Дата окончания торгов'];
    $errors = [];

    foreach ($required as $key) {
        if (isset($lot[$key])) {
            if (empty($lot[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        } else {
            $errors[$key] = 'Это поле отсутствует';
        }

    }

    if ($lot['category'] === 0) {
        $errors['category'] = 'Не выбрана категория';
    }

    // проверяем изображение
    if (isset($_FILES['lot-image']['name']) && ($_FILES['lot-image']['tmp_name'] !== '')) {
        $tmp_name = $_FILES['lot-image']['tmp_name'];
        $file_name = uniqid('', false) . '.jpg';
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($file_info, $tmp_name);

        if ($file_type !== 'image/jpeg') {
            $errors['path'] = 'Загрузите картинку в формате JPG';
        } else {
            if (move_uploaded_file($tmp_name, $file_path . $file_name)) {
                $lot['path'] = $file_path . $file_name;
            } else {
                $errors['path'] = 'Ошибка загрузки файла';
            }

        }
    } else {
        $errors['path'] = 'Вы не загрузили файл';
    }

    // если есть ошибки
    if (count($errors)) {
        $page_content = include_template('add-lot.php', [
            'lot' => $lot,
            'errors' => $errors,
            'dict' => $dict,
            'categories' => $categories]);
    } else {

        //если нет ошибок
        //вносим данные нового лота в базу
        $sql = 'INSERT INTO `lots` (`date_add`,`image_url`, `title`, `description`, `start_price`, `date_end`,
                     `bid_step`, `category_id`, `owner_id`) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, 1);';
        $stmt = db_get_prepare_stmt($link, $sql, [$lot['path'], $lot['title'], $lot['description'], $lot['start_price'], $lot['date_end'],
            $lot['bid_step'], $lot['category']]);

        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $lot_id = mysqli_insert_id($link);
            header('Location: lot.php?id=' . $lot_id);
        } else {
            $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
        }
    }
} else {
    // если пришли не POST запроса
    $page_content = include_template('add-lot.php', ['categories' => $categories]);

}

$layout_content = include_template('layout.php', [
    'page_title' => 'Yeticave - Добавление лота',
    'content' => $page_content,
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);











