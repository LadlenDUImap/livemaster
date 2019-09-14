<?php

/* @var $this app\core\View */
/* @var $values app\core\Container */

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= \app\core\html\Safe::htmlEncode($this->title) ?></title>

    <link href="css/bootstrap-grid.css" rel="stylesheet" media="all" />
    <link href="css/main.css" rel="stylesheet" media="all" />

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
