<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $generators \yiiframe\gii\Generator[] */
/* @var $content string */

$generators = Yii::$app->controller->module->generators;
$this->title = Yii::t('app','欢迎来到Gii');
?>
<div class="default-index">
    <h1 class="border-bottom pb-3 mb-3"><?=Yii::t('app','欢迎来到Gii');?> <small class="text-muted"><?=Yii::t('app','一个可以为您编写代码的神奇工具');?></small></h1>

    <p class="lead mb-5"><?=Yii::t('app','从下面的代码生成器开始享受乐趣');?>:</p>

    <div class="row">
        <?php foreach ($generators as $id => $generator): ?>
        <div class="generator col-lg-4">
            <h3><?= Html::encode($generator->getName()) ?></h3>
            <p><?= $generator->getDescription() ?></p>
            <p><?= Html::a('Start &raquo;', ['default/view', 'id' => $id], ['class' => ['btn', 'btn-outline-secondary']]) ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- <p><a class="btn btn-success" href="http://www.yiiframework.com/extensions/?tag=gii">Get More Generators</a></p> -->

</div>
