<?php
function include_template($name, $data)
{
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function format_price($value)
{
    $value = ceil($value);
    if ($value > 1000) {
        $value = number_format($value, 0, '', ' ');
    }
    return $value . ' <b class="rub">р</b>';
}

function esc($str)
{
    $text = htmlspecialchars($str);
    return $text;
}
