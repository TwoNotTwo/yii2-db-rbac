<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */
namespace twonottwo\db_rbac\views\access;

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('db_rbac', 'Новая роль');
$this->params['breadcrumbs'][] = ['label' => Yii::t('db_rbac', 'Роли'), 'url' => ['role']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="permit-add-role">
    <h3><?= Html::encode($this->title) ?></h3>
    <?php if (!empty($error)) { ?>
        <div class="error-summary">
            <?= implode('<br>', $error); ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-lg-5">
            <?php ActiveForm::begin(['id' => 'add-role-form']); ?>

            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Название роли')); ?>
                <?= Html::textInput('name', '', ['class' => 'form-control']); ?>
                <div style="color:#999; font-size:0.9em">
                    <?= Yii::t('db_rbac', 'Использовать можно: латинские буквы, цифры,"_" и "-"'); ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Описание')); ?>
                <?= Html::textInput('description', '', ['class' => 'form-control']); ?>
            </div>

            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Есть доступ к')); ?>
                <?= Html::checkboxList('permissions', null, $permissions, ['separator' => '<br>']); ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('db_rbac', 'Сохранить'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>