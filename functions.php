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
    $value = number_format($value, 0, '', ' ');
    return $value . ' <b class="rub">р</b>';
}

function esc($str)
{
    $text = htmlspecialchars($str);
    return $text;
}

function get_time_until_midnight()
{
    $next_day = strtotime('tomorrow');
    $diff = $next_day - time();
    $hours = floor($diff / 3600);
    $minutes = floor(($diff % 3600) / 60);
    return  $hours . ':' . $minutes;
}