<?php
/**
 * AccessController for Yii2
 *
 * @author Elle <elleuz@gmail.com>
 * @version 0.1
 * @package AccessController for Yii2
 *
 */
namespace twonottwo\db_rbac\controllers;

use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\validators\RegularExpressionValidator;
use yii\rbac\Role;
use yii\rbac\Permission;


class AccessController extends Controller
{
    protected $error;
    protected $pattern4Role = '/^[a-zA-Z0-9_-]+$/';
    protected $pattern4Permission = '/^[a-zA-Z0-9_\/-]+$/';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * просмотр разрешений у роли.
     * имя роли, разрешения которой нужно вывести берется из URL, параметр name.
     * для повышения безопастности доступ пользователей к данному действию должен быть ограничен
     */
    public function actionViewRole()
    {
        if (Yii::$app->request->get('name')) {
            $role = Yii::$app->request->get('name');

            return $this->render('viewRole', [ 'role' => Html::encode($role), ]);

        } else throw new BadRequestHttpException(Yii::t('yii', 'Page not found.'));
    }

    public function actionRole()
    {
        return $this->render('role');
    }

    public function actionAddRole()
    {
        if (Yii::$app->request->post('name')
            && $this->validate(Yii::$app->request->post('name'), $this->pattern4Role)
            && $this->isUnique(Yii::$app->request->post('name'))
        ){
            $role = Yii::$app->authManager->createRole(Html::encode(Yii::$app->request->post('name')));
            $role->description = Html::encode(Yii::$app->request->post('description'));
            Yii::$app->authManager->add($role);
            $this->setPermissions(Yii::$app->request->post('permissions', []), $role);

            return $this->redirect(Url::toRoute([ 'update-role', 'name' => $role->name ]));
        }

        $permissions = ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'description');

        return $this->render('addRole', [ 'permissions' => $permissions, 'error' => $this->error ]);
    }

    public function actionUpdateRole($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        $permissions = ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'description');
        $role_permit = array_keys(Yii::$app->authManager->getPermissionsByRole($name));

        if ($role instanceof Role) {
            if (Yii::$app->request->post('name')
                && $this->validate(Yii::$app->request->post('name'), $this->pattern4Role)
            ){
                if (Html::encode(Yii::$app->request->post('name')) != $name && !$this->isUnique(Html::encode(Yii::$app->request->post('name')))) {
                    return $this->render('updateRole',
                        [
                            'role' => $role,
                            'permissions' => $permissions,
                            'role_permit' => $role_permit,
                            'error' => $this->error
                        ]
                    );
                }

                $role = $this->setAttribute($role, Yii::$app->request->post());
                Yii::$app->authManager->update($name, $role);
                Yii::$app->authManager->removeChildren($role);
                $this->setPermissions(Yii::$app->request->post('permissions', []), $role);

                return $this->redirect(Url::toRoute([ 'update-role', 'name' => $role->name ]));
            }

            return $this->render('updateRole',
                [
                    'role' => $role,
                    'permissions' => $permissions,
                    'role_permit' => $role_permit,
                    'error' => $this->error
                ]
            );
        } else throw new BadRequestHttpException(Yii::t('yii', 'Page not found.'));
    }

    public function actionDeleteRole($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        if ($role) {
            Yii::$app->authManager->removeChildren($role);
            Yii::$app->authManager->remove($role);
        }

        return $this->redirect(Url::toRoute([ 'role' ]));
    }


    public function actionPermission()
    {
        return $this->render('permission');
    }

    public function actionAddPermission()
    {
        $permission = $this->clear(Html::encode(Yii::$app->request->post('name')));
        if ($permission
            && $this->validate($permission, $this->pattern4Permission)
            && $this->isUnique($permission)
        ){
            $permit = Yii::$app->authManager->createPermission($permission);
            $permit->description = Html::encode(Yii::$app->request->post('description', ''));
            Yii::$app->authManager->add($permit);

            return $this->redirect(Url::toRoute([ 'update-permission', 'name' => $permit->name ]));
        }

        return $this->render('addPermission', [ 'error' => $this->error ]);
    }

    public function actionUpdatePermission($name)
    {
        $permit = Yii::$app->authManager->getPermission($name);
        if ($permit instanceof Permission) {
            $permission = $this->clear(Html::encode(Yii::$app->request->post('name')));
            if ($permission && $this->validate($permission, $this->pattern4Permission)) {
                if ($permission!= $name && !$this->isUnique($permission)) {
                    return $this->render('updatePermission',
                        [
                            'permission' => $permit,
                            'error' => $this->error
                        ]
                    );
                }

                $permit->name = $permission;
                $permit->description = Html::encode(Yii::$app->request->post('description', ''));
                Yii::$app->authManager->update($name, $permit);

                return $this->redirect(Url::toRoute([ 'update-permission', 'name' => $permit->name ]));
            }

            return $this->render('updatePermission', [ 'permission' => $permit, 'error' => $this->error ]);

        } else throw new BadRequestHttpException(Yii::t('yii', 'Page not found.'));
    }

    public function actionDeletePermission($name)
    {
        $permit = Yii::$app->authManager->getPermission($name);
        if ($permit) {
            Yii::$app->authManager->remove($permit);
        }
        return $this->redirect(Url::toRoute([ 'permission' ]));
    }

    protected function setAttribute($object, $data)
    {
        $object->name = $data['name'];
        $object->description = $data['description'];

        return $object;
    }

    protected function setPermissions($permissions, $role)
    {
        foreach ($permissions as $permit) {
            $new_permit = Yii::$app->authManager->getPermission($permit);
            Yii::$app->authManager->addChild($role, $new_permit);
        }
    }

    protected function validate($field, $regex)
    {
        $validator = new RegularExpressionValidator([ 'pattern' => $regex ]);
        if ($validator->validate($field, $error)) {
            return true;
        } else {
            $this->error[] = Yii::t('db_rbac', 'Значение "{field}" содержит не допустимые символы', ['field' => $field]);
            return false;
        }
    }

    protected function isUnique($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        $permission = Yii::$app->authManager->getPermission($name);
        if ($permission instanceof Permission) {
            $this->error[] = Yii::t('db_rbac', 'Разрешение с таким именем уже существует') .':'. $name;
            return false;
        }
        if ($role instanceof Role) {
            $this->error[] = Yii::t('db_rbac', 'Роль с таким именем уже существует') .':'. $name;
            return false;
        }
        return true;
    }

    protected function clear($value)
    {
        if (!empty($value)) {
            $value = trim($value, "/ \t\n\r\0\x0B");
        }
        return $value;
    }
}