<?php
require_once('data.php');
require_once('functions.php');
require_once('config.php');


if (!$link) {
    $error = mysqli_connect_error();
    $main_content = include_template('error.php', ['error' => $error]);
} else {
    $sql = 'SELECT id, name, style_name FROM categories ORDER BY id';
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        show_error($main_content, $error);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $form = $_POST['signup'];
    $errors = [];
    $required = ['email', 'password', 'name', 'contacts'];
    $dict = ['email' => 'E-Mail', 'password' => 'Пароль', 'path' => 'Аватар',
        'name' => 'Имя', 'contacts' => 'Контакты'];
    foreach ($required as $key) {
        if (isset($form[$key])) {
            if (empty($form[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        } else {
            $errors[$key] = 'Это поле отсутствует';
        }
    }


    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['incorrect_email'] = 'Введите корректный email';
    }

    // проверяем изображение
    if (isset($_FILES['file']['name']) && ($_FILES['file']['tmp_name'] !== '')) {
        $tmp_name = $_FILES['file']['tmp_name'];
        $file_name = uniqid('', false) . '.jpg';
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($file_info, $tmp_name);

        if ($file_type !== 'image/jpeg') {
            $errors['path'] = 'Загрузите картинку в формате JPG';
        } else {
            if (move_uploaded_file($tmp_name, $avatar_path  . $file_name)) {
                $form['path'] = $avatar_path . $file_name;

            } else {
                $errors['path'] = 'Ошибка загрузки файла';
            }

        }
    } else {
        $errors['path'] = 'Вы не загрузили файл';
    }

    if (!count($errors)) {

        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        if ($res) {
            if (mysqli_num_rows($res) > 0) {
                $errors[] = 'Пользователь с этим email уже зарегистрирован';
            } else {
                $password = password_hash($form['password'], PASSWORD_DEFAULT);
                $sql = 'INSERT INTO users (date_add, email, name, password, contacts, avatar_url)
                        VALUES (NOW(),?, ?, ?, ?, ?)';
                $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'],
                    $password , $form['contacts'], $form['path']]);
                $res = mysqli_stmt_execute($stmt);

            if ($res && empty($errors)) {
                header("Location: /enter.php");
                exit();
            } else {
                $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
            }
            }

        } else {
            $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
        }


    } else {
        $main_content = include_template('register.php', [
            'form' => $form,
            'errors' => $errors,
            'dict' => $dict,
            'categories' => $categories]);
    }
}

if (!$main_content) {
    $main_content = include_template('register.php', [
        'form' => $form,
        'errors' => $errors,
        'dict' => $dict,
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'page_title' => 'Yeticave - Регистрация пользователя',
    'content' => $main_content,
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
