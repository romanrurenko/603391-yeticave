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

function get_time_until_date_end($date_end)
{
    $next_day = strtotime($date_end);
    $diff = $next_day - time();
    if ($diff < 0) {
        $result = 'закрыт';
    } else {
        $days = floor($diff / 3600) / 24;
        $hours = floor(($diff % 3600) / 60);
        $minutes = floor($diff % 60);
        $result = ($days >= 1) ? sprintf("%02d", $days) . ' дн.' : sprintf("%02d:%02d", $hours, $minutes);
    };

    return $result;
}

function show_error(&$content, $error) {
    $content = include_template('error.php', ['error' => $error]);
}


function db_get_prepare_stmt($link, $sql, $data = []) {
$stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';


        $func(...$values);
    }


    return $stmt;
}