<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\entities\User;
use app\entities\Conference;

app\assets\AppAsset::register($this);

//Yii::$app->authManager->invalidateCache(); //очистка кэша ролей

$conference_now = Conference::find()
       ->where(['<=', '(start_time - ' . Yii::$app->setting->get('registerOpen') .')', time()])
    ->andWhere(['is', 'end_time', null])
    ->andWhere(['status' => Conference::STATUS_ACTIVE,])
    ->andWhere(['deleted' => 0])
     ->orderBy(['start_time' => SORT_ASC])
         ->one();

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
        'header', [
            'conference_now' => $conference_now,
        ]
    ) ?>

    <?= $this->render(
        '/html_block/element/modal'
    ) ?>

    <?= $this->render(
        'left', [
            'conference_now' => $conference_now,
        ]
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
