<?php

/* @var $certificate \app\entities\Certificate */

$points = 2;
$provider = ''

?>

<html>
<head>
    <meta charset="UTF-8" />
</head>
<body>
<div id="container" style="display: inline-block; position: relative;">
    <p style="position:absolute; top:222px; font-family:&quot;Arial&quot;; color:black; font-size:27px; left:0; right:0; width:100%; text-align:center;">
        <?= $certificate->conference->title ?>
    </p>

    <img src="http://register-srv.dwbx.ru/img/Certificate2.png" alt="" style="width:1754px; height:1216px;">

    <p style="position:absolute; top:287px; font-family:&quot;Arial&quot;; color:black; font-size:30px; left:0; right:0; width:100%; text-align:center;">
        <?= Yii::$app->formatter->asDate($certificate->conference->start_time, 'php: d.m.Y') ?>
    </p>

    <p style="position:absolute; top:535px; font-family:&quot;Arial&quot;; color:black; font-size:26px; left:790px; width:188px; text-align:center;">
        <?= $certificate->document_series ?>
    </p>

    <p style="position:absolute; top:636px; font-family:&quot;Arial&quot;; color:black; font-size:30px; left:0; right:0; width:100%; text-align:center;">
        <?= $certificate->userFullName ?>
    </p>

    <p style="position:absolute; top:748px; font-family:&quot;Arial&quot;; color:black; font-size:26px; right:545px; text-align:center; line-height:53px;">
        <?= $points ?>
    </p>

    <p style="position:absolute; top:787px; font-family:&quot;Arial&quot;; color:black; font-size:30px; left:0; right:0; padding:0px 601px; text-align:center; line-height:53px;">

    </p>

    <p style="position:absolute; top:925px; font-family:&quot;Arial&quot;; color:black; font-size:26px; left:1025px; width:200px; text-align:center;">
        <?= $certificate->verification_code ?>
    </p>
</div>
</body>
</html>
