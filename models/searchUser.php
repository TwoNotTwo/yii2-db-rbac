<?php
/**
 * @copyright Copyright (c) 2016 TwoNotTwo <2not2.github@gmail.com>
 */
namespace twonottwo\db_rbac\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User; //Изменить на тот путь, где находится модель User

class searchUser extends User
{

    public function rules()
    {
        return [
            [['username', 'email'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
