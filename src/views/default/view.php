<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiframe\gii\components\ActiveField;
use yiiframe\gii\CodeFile;

/* @var $this yii\web\View */
/* @var $generator yiiframe\gii\Generator */
/* @var $id string panel ID */
/* @var $form yii\widgets\ActiveForm */
/* @var $results string */
/* @var $hasError bool */
/* @var $files CodeFile[] */
/* @var $answers array */

$this->title = $generator->getName();
$this->params['breadcrumbs'][] = ['label' => $this->title];

$templates = [];
foreach ($generator->templates as $name => $path) {
    $templates[$name] = "$name ($path)";
}
?>

<div class="default-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= $generator->getDescription() ?></p>

    <?php $form = ActiveForm::begin([
        'id' => "$id-generator",
        'successCssClass' => 'is-valid',
        'errorCssClass' => 'is-invalid',
        'validationStateOn' => ActiveForm::VALIDATION_STATE_ON_INPUT,
        'fieldConfig' => [
            'class' => ActiveField::className(),
            'hintOptions' => ['tag' => 'small', 'class' => 'form-text text-muted'],
            'errorOptions' => ['class' => 'invalid-feedback']
        ],
    ]); ?>
        <div class="row">
            <div class="col-lg-8 col-md-10" id="form-fields">
                <?= $this->renderFile($generator->formView(), [
                    'generator' => $generator,
                    'form' => $form,
                ]) ?>
                <?= $form->field($generator, 'template')
                    ->sticky()
                    ->hint(Yii::t('app','请选择应该使用哪一组模板来生成代码。'))
                    ->label(Yii::t('app','代码模板'))
                    ->dropDownList($templates) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app','预览'), ['name' => 'preview', 'class' => 'btn btn-primary']) ?>

                    <?php if (isset($files)): ?>
                        <?= Html::submitButton(Yii::t('app','生成'), ['name' => 'generate', 'class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        if (isset($results)) {
            echo $this->render('view/results', [
                'generator' => $generator,
                'results' => $results,
                'hasError' => $hasError,
            ]);
        } elseif (isset($files)) {
            echo $this->render('view/files', [
                'id' => $id,
                'generator' => $generator,
                'files' => $files,
                'answers' => $answers,
            ]);
        }
        ?>
    <?php ActiveForm::end(); ?>
</div>

