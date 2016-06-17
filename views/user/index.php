<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */
namespace twonottwo\db_rbac\views\user;

use Yii;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use twonottwo\db_rbac\models\Profile;

$this->title = Yii::t('db_rbac', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => SerialColumn::className()],

        [
            'attribute' => 'p_username',
            'label' => Yii::t('db_rbac', 'Имя пользователя'),
            'value' => function($model) { return Profile::findIdentity($model->id)->p_username; },
        ],

        [
            'attribute' => 'username',
            'label' => Yii::t('db_rbac', 'Логин'),
        ],

        [
            'attribute' => 'email',
            'label' => Yii::t('db_rbac', 'Почтовый ящик'),
        ],

        [
            'label' => Yii::t('db_rbac', 'Роль'),
            'format' => ['html'],
            'value' => function($model) { return implode('<br>',array_keys(ArrayHelper::map(Yii::$app->authManager->getRolesByUser($model->id), 'description', 'description')));}
        ],

        [
            'class' => ActionColumn::className(),
            'contentOptions' => ['width' => '23pt'],
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        Url::to(['/permit/user/view', 'id' => $model->id]),
                        [
                            'title' => Yii::t('db_rbac', 'Детальный просмотр и редактирование'),
                        ]
                    );
                },
            ]
        ],

    ]
]);


