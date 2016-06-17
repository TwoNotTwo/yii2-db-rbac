<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */

namespace twonottwo\db_rbac\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * BUG_REPORT:
 *
 * 160616 13:14 TwoNotTwo
 * Если статус профиля будет STATUS_DELETED, то его поля ФИО, день рождения не получится обновить
 * (обязательный парамтр id отсутствует)
 */

class Profile extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        return '{{%profile}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['user_id'], 'required'],
            [['birthday'], 'safe'],
            [['p_username'], 'string', 'max' => 255],
            [['user_id'], 'unique'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'p_username' => Yii::t('db_rbac', 'ФИО'),
            'birthday' => Yii::t('db_rbac' , 'Дата рождения'),
            'status' => 'Status',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => Html::encode($id), 'status' => self::STATUS_ACTIVE]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }
}
