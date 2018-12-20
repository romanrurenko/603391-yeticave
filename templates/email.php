<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>

<table>
    <h1>Поздравляем с победой</h1>
    <p>Здравствуйте, <?=$user['name']??''?></p>
    <p>Ваша ставка для лота <a href="/lot.php?id=<?=$user['lot_id']??''?>"><?=$user['title']??''?></a> победила.</p>
    <p>Перейдите по ссылке <a href="/my-bids.php?filter=<?=$user['user_id']??''?>">мои ставки</a>,
        чтобы связаться с автором объявления.</p>

    <small>Интернет Аукцион "YetiCave"</small>
</table>
</body>
</html>