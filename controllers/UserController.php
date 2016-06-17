<?php
/**
 * Controller to attache role for user for Yii2
 *
 * @author Elle <elleuz@gmail.com>
 * @version 0.1
 * @package UserController for Yii2
 *
 */
namespace twonottwo\db_rbac\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

use twonottwo\db_rbac\interfaces\UserRbacInterface;
use twonottwo\db_rbac\models\searchUser;
use twonottwo\db_rbac\models\Profile;


class UserController extends Controller
{
    public $moduleName = 'permit';

    public function beforeAction($action)
    {
        if (empty(Yii::$app->controller->module->params['userClass']))
            throw new BadRequestHttpException(Yii::t('db_rbac', 'Необходимо указать класс User в настройках модуля'));

        $user = new Yii::$app->controller->module->params['userClass']();

        if (!$user instanceof UserRbacInterface)
            throw new BadRequestHttpException(Yii::t('db_rbac', 'UserClass должен реализовывать интерфейс twonottwo\db_rbac\UserRbacInterface'));

        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'update' => ['post'],
                    '*' => ['get'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new searchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    public function actionView($id)
    {
        $id = Html::encode($id);
        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
        $user_permit = array_keys(Yii::$app->authManager->getRolesByUser($id));
        $user = $this->findUser($id);
        $profile = $this->findProfile($id);

        return $this->render('view',
            [
                'user' => $user,
                'roles' => $roles,
                'user_permit' => $user_permit,
                'moduleName' => Yii::$app->controller->module->id,
                'profile' => $profile,
            ]
        );
    }

    public function actionUpdate($id)
    {
        $id = Html::encode($id);
        $user = $this->findUser($id);
        Yii::$app->authManager->revokeAll($user->getId());
        if(Yii::$app->request->post('roles')) {
            foreach(Yii::$app->request->post('roles') as $role) {
                $new_role = Yii::$app->authManager->getRole(Html::encode($role));
                Yii::$app->authManager->assign($new_role, $user->getId());
            }
        }
        return $this->redirect(Url::to([ "/".Yii::$app->controller->module->id."/user/view", 'id' => $user->getId() ]));
    }

    private function findUser($id)
    {
        $id = Html::encode($id);
        $class = new Yii::$app->controller->module->params['userClass']();
        $user = $class::findIdentity($id);
        if(empty($user)) {
            throw new NotFoundHttpException(Yii::t('db_rbac', 'Пользователь не найден'));
        } else return $user;
    }

    public function findProfile($id)
    {
        /**
         * наличие профиля обязательно. Если к учетной записи нет привязанного профиля, то он создается
         */
        $id = Html::encode($id);
        $class = Profile::className();
        $profile = $class::findIdentity($id);
        if(empty($profile)) {
            $profile = new Profile();
            $profile->user_id = $id;
            $profile->save();
        }

        return $profile;
    }
}