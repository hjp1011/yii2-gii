<?php

use yii\widgets\Menu;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
/* @var $this \yii\web\View */
/* @var $content string */

$asset = yii\gii\GiiAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini fixed">
        <?php $this->beginBody() ?>
        

        <div class="content">
            <?= $content ?>
        </div>
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
