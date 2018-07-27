<?php

/* @var $this \yii\web\View */

use yii\bootstrap\Modal;

/**
 * Modal form
 */
try {
    echo Modal::widget([
        'id' => 'modalForm',
         'options' => [
             'data-keyboard' => 'false',
             'data-backdrop' => 'static'
         ],
        'size' => 'modal-ultra-lg',
    ]);
} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
}
