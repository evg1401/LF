<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action="/passwordRecovery" method="post">
    <input type="email" name="email" placeholder="email">
    <input type="submit" name="submit" value="Отправить">
</form>
<?= $res ?>
</body>
</html>
<?php

use RedBeanPHP\R;

$find = R::load('users', 1);

if (password_verify('600df27831b69', $find->password)) {
    echo 'совпадает';
}
echo '<br>';

$login = new \Core\Auth\Login();
$r = $login->login('evg1401', '600df27831b69');
var_dump($r);
