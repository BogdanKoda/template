<?php
use app\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

/** @var $content */
?>
<?php $this->beginPage() ?>

    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <meta charset="<?= Yii::$app->charset ?>">
        <?php $this->registerCsrfMetaTags() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="/Login_v10/images/icons/favicon.ico"/>
        <link rel="stylesheet" type="text/css" href="/Login_v10/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/Login_v10/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="/Login_v10/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="/Login_v10/vendor/animate/animate.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="/Login_v10/vendor/css-hamburgers/hamburgers.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="/Login_v10/vendor/animsition/css/animsition.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="/Login_v10/vendor/select2/select2.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="/Login_v10/vendor/daterangepicker/daterangepicker.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="/Login_v10/css/util.css">
        <link rel="stylesheet" type="text/css" href="/Login_v10/css/main.css">
        <!--===============================================================================================-->
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?=$content; ?>


    <div id="dropDownSelect1"></div>

    <!--===============================================================================================-->
    <script src="/Login_v10/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="/Login_v10/vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="/Login_v10/vendor/bootstrap/js/popper.js"></script>
    <script src="/Login_v10/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="/Login_v10/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="/Login_v10/vendor/daterangepicker/moment.min.js"></script>
    <script src="/Login_v10/vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="/Login_v10/vendor/countdowntime/countdowntime.js"></script>
    <!--===============================================================================================-->
    <script src="/Login_v10/js/main.js"></script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>