<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $email string */
/* @var $password string */

?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<p>
    <strong>Ссылка на личный кабинет:</strong> <a href="http://cert.dwbx.ru">cert.dwbx.ru</a>
</p>
<p>
    <strong>Логин:</strong> <?= $email ?>
    <br>
    <strong>Пароль:</strong> <?= $password ?>
</p>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
