<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\entities\User;
use app\entities\Conference;

app\assets\AppAsset::register($this);

//Yii::$app->authManager->invalidateCache(); //очистка кэша ролей

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-black-light sidebar-mini sidebar-collapse">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render(
        'header'
    ) ?>

    <?= $this->render(
        '/html_block/element/modal'
    ) ?>

    <?= $this->render(
        'left'
    ) ?>

    <?= $this->render(
        'content',
        ['content' => $content]
    ) ?>

    <?= $this->render(
        'footer'
    ) ?>

    <?php if (Yii::$app->user->can(User::ROLE_PARTICIPANT) || Yii::$app->user->can(User::ROLE_ADMIN)): ?>
        <?= $this->render(
            'user-sidebar'
        ) ?>
    <?php endif; ?>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
