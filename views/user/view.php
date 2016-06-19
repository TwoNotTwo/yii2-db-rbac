<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 *
 * Todo:
 *
 * 160616 13:15 TwoNotTwo
 * Добавить ввод даты рождения через виджет "календарь"
 */

namespace twonottwo\db_rbac\views\user;

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('db_rbac', 'Детальный просмотр и редактирование');
$this->params['breadcrumbs'][] = ['label' => Yii::t('db_rbac', 'Пользователи'), 'url' => ['/permit/user']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-lg-5">
        <h4><?= Yii::t('db_rbac', 'Личные данные пользователя'); ?></h4>

        <?php $form = ActiveForm::begin(['action' => ["/{$moduleName}/profile/update", 'id' => $profile->getId()]]); ?>
        <?= $form->field($profile, 'p_username')->textInput(['autocomplete' => 'off']); ?>
        <?= $form->field($profile, 'birthday')->textInput(['autocomplete' => 'off']);?>
        <?= $form->field($user, 'email')->textInput(['autocomplete' => 'off']);?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('db_rbac', 'Обновить'), ['class' => 'btn btn-primary']); ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="col-lg-7">
        <h4><?= Yii::t('db_rbac', 'Назначение ролей'); ?></h4>

        <?php $form = ActiveForm::begin(['action' => ["/{$moduleName}/user/update", 'id' => $user->getId()]]); ?>

        <?= Html::checkboxList('roles', $user_permit, $roles, [
                'item' => function ($index, $label, $name, $checked, $value){
                    (strlen($label) <= 0)? $label = $value : false;
                    return Html::checkbox($name, $checked, [
                        'value' => Html::encode($value),
                        'label' => '<label for="' . Html::encode($value) . '">' .  Html::encode($label) . '</label>'.
                            Html::a(
                                '<span class="glyphicon glyphicon-eye-open" style="padding-left:4pt;"></span>',
                                Url::to(['/permit/access/view-role', 'name' => Html::encode($value)]),
                                ['title' => Yii::t('db_rbac', 'Посмотреть, что доступно этой роли'),]
                            ),
                        ]
                    );
                },
                'separator' => '<br>',
            ]);
        ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('db_rbac', 'Назначить'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>




