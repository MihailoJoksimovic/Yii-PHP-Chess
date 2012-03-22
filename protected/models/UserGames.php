<?php

/**
 * This is the model class for table "games_users".
 *
 * The followings are the available columns in table 'games_users':
 * @property string $id
 * @property string $game_id
 * @property string $user_id
 * @property string $dt_added
 * 
 * @property User $user
 * @property Game $game
 */
class UserGames extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserGames the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'games_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('game_id, user_id, dt_added', 'required'),
			array('game_id, user_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, game_id, user_id, dt_added', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'game' => array(self::BELONGS_TO, 'Game', 'game_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'game_id' => 'Game',
			'user_id' => 'User',
			'dt_added' => 'Dt Added',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('game_id',$this->game_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('dt_added',$this->dt_added,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	/**
	 *
	 * @param type $limit
	 * @return array|Game
	 */
	public function getRecentGames($limit = 10)
	{
		$sql = "SELECT game_id

			FROM games_users

			INNER JOIN game ON games_users.`game_id` = game.`id`

			WHERE
				game.`is_finished` = 0

			GROUP BY games_users.`game_id`

			HAVING COUNT(games_users.game_id) = 1";
		
		$query = Yii::app()->db->createCommand($sql);
		
		$rows = $query->queryAll();
		
		$return = array();
		
		foreach ($rows AS $row)
		{
			$return[] = Game::model()->findByPk($row['game_id']);
		}
		
		return $return;
	}
}