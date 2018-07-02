<?php

/* @var $this core\View */
/* @var $values core\Container */

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Тестовое задание Livemaster</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <?= $this->css ?>

</head>
<body>

<div class="container">
    <?= $values->content ?>
</div>

<?= $this->js ?>

</body>
</html>
