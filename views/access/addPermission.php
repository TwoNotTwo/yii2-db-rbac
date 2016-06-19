<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */
namespace twonottwo\db_rbac\views\access;

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('db_rbac', 'Новое разрешение');
$this->params['breadcrumbs'][] = ['label' => Yii::t('db_rbac', 'Разрешения'), 'url' => ['permission']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="permit-add-permission">
    <h3><?= Html::encode($this->title) ?></h3>

    <?php if (!empty($error)) { ?>
        <div class="error-summary">
            <?= implode('<br>', $error); ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-lg-5">
            <?php ActiveForm::begin([ 'id' => 'add-permission-form']); ?>
            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Название разрешения',['class' => 'form-control'])); ?>
                <?= Html::textInput('name', '', ['class' => 'form-control', 'autofocus' => true, 'autocomplete' => 'off']);  ?>
                <div style="color:#999; font-size:0.9em">
                    <?= Yii::t('db_rbac', 'Формат записи: app-name/module/controller/action'); ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Описание', ['class' => 'form-control'])); ?>
                <?= Html::textInput('description', '', ['class' => 'form-control', 'autocomplete' => 'off']); ?>
            </div>
        </div>

        <div class="col-lg-3">
            <?= Html::submitButton(Yii::t('db_rbac', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


