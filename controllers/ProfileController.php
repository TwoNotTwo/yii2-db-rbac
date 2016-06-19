<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */

namespace twonottwo\db_rbac\controllers;


use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use common\models\User;
use twonottwo\db_rbac\models\Profile;


class ProfileController extends Controller
{
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //обновление поля email
            $user = User::findIdentity($model->user_id);
            $param = Yii::$app->request->post('User');
            $user->email = $param['email'];
            $user->save();
            return $this->redirect(Url::to(["/".Yii::$app->controller->module->id."/user/view", 'id' => $model->user_id]));
        } else {
            return $this->render('update',
                [
                    'model' => $model,
                ]
            );
        }
    }

    protected function findModel($id)
    {
        $model = Profile::findOne($id);
        if ($model !== null) {
            return $model;
        } else throw new NotFoundHttpException('The requested page does not exist.');
    }
}
