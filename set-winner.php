<?php

require_once('functions.php');
require_once('config.php');


if (!$link) {
    $error = mysqli_connect_error();
    $main_content = include_template('error.php', ['error' => $error]);

} else {

    $sql = "SELECT id, name, style_name FROM categories ORDER BY id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $main_content = select_error($link);
    };



