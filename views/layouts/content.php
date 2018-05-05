<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\widgets\Breadcrumbs;
use yii2mod\alert\Alert;

?>

<div class="content-wrapper">
    <?php if (isset($this->params['breadcrumbs'])): ?>
    <section class="content-header">
        <?php try {
            echo Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
            );
        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        } ?>
    </section>
    <?php endif; ?>

    <section class="content">
        <?php try {
           echo Alert::widget();
        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        } ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <strong>Все права защищены</strong> <span class="glyphicon glyphicon-copyright-mark"></span>
</footer>

<div class='control-sidebar-bg'></div>
