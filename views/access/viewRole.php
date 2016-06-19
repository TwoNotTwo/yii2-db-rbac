<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */

namespace twonottwo\db_rbac\views\access;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('db_rbac', 'Разрешения роли');
$model = Yii::$app->authManager->getRole($role);
$roleName = ($model->description) >0 ? $model->description : $model->name;
?>

<h3 class="text-center"><?= $roleName ?></h3>

<div class="row text-center">
    <?php
        $permissions = ArrayHelper::map(Yii::$app->authManager->getPermissionsByRole($role), 'name', 'description');
        foreach ($permissions as $key => $value){
            echo (strlen($value)) > 0 ? $value : $key;
            echo '<br>';
        }
    ?>
</div>

<div class="row">
    <?= Html::a(Yii::t('db_rbac', 'Назад'), Yii::$app->request->referrer, ['class' => 'btn btn-info']); ?>
    <?= Html::a(Yii::t('db_rbac', 'Редактировать'), Url::to(['/permit/access/update-role', 'name' => $role]), ['class' => 'btn btn-primary']); ?>
</div>

