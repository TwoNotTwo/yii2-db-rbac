<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */

namespace twonottwo\db_rbac\views\access;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('db_rbac', 'Редактирование разрешения');
$this->params['breadcrumbs'][] = ['label' => Yii::t('db_rbac', 'Разрешения'), 'url' => ['permission']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permit-update-permission">
    <h3>
        <?php $title = (strlen($permission->description) <= 0) ? $permission->name : $permission->description; ?>
        <?= Html::encode($this->title. ': ' . $title); ?>
    </h3>

    <?php if (!empty($error)) { ?>
        <div class="error-summary">
            <?= implode('<br>', $error); ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-lg-5">
            <?php ActiveForm::begin(['id' => 'update-permission']); ?>

            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Название разрешения')); ?>
                <?= Html::textInput('name', $permission->name, ['class' => 'form-control', 'autocomplete' => 'off']); ?>
                <div style="color:#999; font-size:0.9em">
                    <?= Yii::t('db_rbac', 'Формат записи: app-name/module/controller/action'); ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Описание')); ?>
                <?= Html::textInput('description', $permission->description, ['class' => 'form-control', 'autocomplete' => 'off']); ?>
            </div>


            <div class="form-group">
                    <?= Html::submitButton(Yii::t('db_rbac', 'Обновить'), ['class' => 'btn btn-success']) ?>

                    <?=
                        Html::a(Yii::t('db_rbac', 'Удалить'), Url::toRoute(['delete-permission','name' => $permission->name]),
                            [
                                'class' => 'btn btn-danger',
                                'data-confirm' => Yii::t('db_rbac', 'Удалить разрешение').': '.$permission->name.' ?',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]
                        );
                    ?>
            </div>




            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>