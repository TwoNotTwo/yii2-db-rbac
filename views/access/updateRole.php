<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */

namespace twonottwo\db_rbac\views\access;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('db_rbac', 'Редактирование роли');
$this->params['breadcrumbs'][] = ['label' => Yii::t('db_rbac', 'Роли'), 'url' => ['role']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permit-update-role">
    <h3><?= Html::encode($this->title. ': ' . $role->name) ?></h3>

    <?php if (!empty($error)) { ?>
        <div class="error-summary">
            <?= implode('<br>', $error); ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-lg-5">
            <?php ActiveForm::begin(['id' => 'update-role']); ?>

            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Название роли')); ?>
                <?= Html::textInput('name', $role->name, ['class' => 'form-control']); ?>
            </div>

            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Описание')); ?>
                <?= Html::textInput('description', $role->description, ['class' => 'form-control']); ?>
            </div>

            <div class="form-group">
                <?= Html::label(Yii::t('db_rbac', 'Есть доступ к')); ?>
                <?= Html::checkboxList('permissions', $role_permit, $permissions, ['separator' => '<br>']); ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('db_rbac', 'Обновить'), ['class' => 'btn btn-success']) ?>

                <?=
                Html::a(Yii::t('db_rbac', 'Удалить'), Url::toRoute(['delete-role','name' => $role->name]),
                    [
                        'class' => 'btn btn-danger',
                        'data-confirm' => Yii::t('db_rbac', 'Удалить роль').': '.$role->name.' ?',
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
