<?php

use app\core\Url;

/* @var $this app\core\View */
/* @var $values app\core\Container */

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $this->values->title ?></title>

    <link href="<?= Url::to('/css/main.css') ?>" rel="stylesheet" media="all"/>

    <?= $this->css ?>

    <script>
        function cl(msg) {
            <?php if (LM_DEBUG): ?>
            if (window.console) {
                console.log("LM_DEBUG: " + msg);
            }
            <?php endif; ?>
        }
    </script>
</head>
<body>

<div class="container">
    <nav class="nav">
        <ul>
            <li><a href="<?= Url::to('/') ?>">Пользователи</a></li>
            <li><a href="<?= Url::to('/city/') ?>">Города</a></li>
        </ul>
    </nav>

    <?= $values->raw('content') ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="<?= Url::to('/js/main.js') ?>"></script>

<?= $this->js ?>

</body>
</html>
