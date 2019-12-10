<?php

use app\core\Lm;

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

    <!--<link href="<?/*= Lm::$app->url->to('/css/jquery-ui.css') */?>" rel="stylesheet" media="all"/>-->
    <link href="<?= Lm::$app->url->to('/css/main.css') ?>" rel="stylesheet" media="all"/>

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
            <li><a href="<?= Lm::$app->url->to('/') ?>">Пользователи</a></li>
            <li><a href="<?= Lm::$app->url->to('/city/') ?>">Города</a></li>
        </ul>
    </nav>

    <?= $values->raw('content') ?>
</div>

<!--<div id="dialog" title="Инфо:">
    <p id="dialog-info"></p>
</div>-->

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>-->
<script src="<?= Lm::$app->url->to('/js/jquery.min.js') ?>"></script>
<!--<script src="<?/*= Lm::$app->url->to('/js/jquery-ui.js') */?>"></script>-->
<script src="<?= Lm::$app->url->to('/js/main.js') ?>"></script>

<?= $this->js ?>

</body>
</html>
