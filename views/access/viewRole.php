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
$roleName = Yii::$app->authManager->getRole($role)->description;
?>

<h3 class="text-center"><?= $roleName ?></h3>

<div class="row text-center">
    <?php
        $permissions = ArrayHelper::map(Yii::$app->authManager->getPermissionsByRole($role), 'description', 'description');
        if ($permissions) {
            echo implode('<br>',array_keys($permissions));
        } else echo Yii::t('db_rbac', 'Этой роли еще не присвоены разрешения');
    ?>
</div>

<div class="row">
    <?= Html::a(Yii::t('db_rbac', 'Назад'), Yii::$app->request->referrer, ['class' => 'btn btn-info']); ?>
    <?= Html::a(Yii::t('db_rbac', 'Редактировать'), Url::to(['/permit/access/update-role', 'name' => $role]), ['class' => 'btn btn-primary']); ?>
</div>

