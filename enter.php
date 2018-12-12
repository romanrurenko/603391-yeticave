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


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $form = $_POST['login'];
    $errors = [];
    $required = ['email', 'password'];
    foreach ($required as $key) {
        if (isset($form[$key])) {
            if (empty($form[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        } else {
            $errors[$key] = 'Это поле отсутствует';
        }
    }

    $email = mysqli_real_escape_string($link, $form['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if ((!count($errors)) && $user) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        }
        else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    }
    else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template('enter.php', ['form' => $form, 'errors' => $errors]);
    }
    else {
        header('Location: /index.php');
        exit();
    }
}
else {
    if (isset($_SESSION['user'])) {
        $page_content = include_template('index.php', ['username' => $_SESSION['user']['name']]);

    }
    else {
        $page_content = include_template('enter.php', []);
    }
}



if (!$main_content) {
    $main_content = include_template('enter.php', [
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
