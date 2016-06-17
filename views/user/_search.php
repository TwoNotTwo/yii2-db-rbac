<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */
namespace twonottwo\db_rbac\views\user;

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'username') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
