<?php

/**
 * This is the model class for table "game".
 *
 * The followings are the available columns in table 'game':
 * @property string $id
 * @property \Libs\ChessGame $Data
 * @property integer $is_finished
 * @property string $dt_added
 * @property string $game_hash
 */
class Game extends CActiveRecord
{
	
	protected $_data;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ga the static model class
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
		return 'game';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('is_finished', 'numerical', 'integerOnly'=>true),
			array('game_hash', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, data, is_finished, dt_added, game_hash', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'data' => 'Data',
			'is_finished' => 'Is Finished',
			'dt_added' => 'Dt Added',
			'game_hash' => 'Game Hash',
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
		$criteria->compare('data',$this->data,true);
		$criteria->compare('is_finished',$this->is_finished);
		$criteria->compare('dt_added',$this->dt_added,true);
		$criteria->compare('game_hash',$this->game_hash,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function generateHash()
	{
		do
		{
			$hash = "";
			for ($i = 0; $i < 10; $i++)
			{
				$range = array_merge(range('a', 'z'), range('A', 'Z'));
				
				shuffle($range);
				
				$hash .= array_pop($range);
			}
			
		} while ($this->findByAttributes(array('game_hash' => $hash)));
		
		return $hash;
	}
	
	protected function beforeSave()
	{
		$this->data	= serialize($this->data);
		
		return parent::beforeSave();
	}
	
	public function getData()
	{
		// Check if serialized :-D
		
		$data = @unserialize($this->data);
		
		if ($data !== false)
		{
			return $data;
		}
		else
		{
			return $this->data;
		}
	}
	
	public function setData($value)
	{
		$this->data = $value;
	}
	
//	
//	protected function afterFind()
//	{
//		parent::afterFind();
//
//		$this->data = unserialide($this->data);
//	}
}