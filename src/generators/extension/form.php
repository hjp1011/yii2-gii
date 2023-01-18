<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yiiframe\gii\generators\extension\Generator */

?>
<div class="alert alert-info">
    <?=Yii::t('app','请细阅')?>
    <?= \yii\helpers\Html::a('Extension Guidelines', 'http://www.yiiframework.com/doc-2.0/guide-structure-extensions.html', ['target'=>'new']) ?>
    <?=Yii::t('app','在创建扩展之前。')?>
</div>
<div class="module-form">
<?php
    echo $form->field($generator, 'vendorName');
    echo $form->field($generator, 'packageName');
    echo $form->field($generator, 'namespace');
    echo $form->field($generator, 'type')->dropDownList($generator->optsType());
    echo $form->field($generator, 'keywords');
    echo $form->field($generator, 'license')->dropDownList($generator->optsLicense(), ['prompt'=>'Choose...']);
    echo $form->field($generator, 'title');
    echo $form->field($generator, 'description');
    echo $form->field($generator, 'authorName');
    echo $form->field($generator, 'authorEmail');
    echo $form->field($generator, 'outputPath');
?>
</div>
