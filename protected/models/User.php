<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property integer $is_confirmed
 * @property string $dt_added
 * @property string $password
 */
class User extends CActiveRecord
{
	public $repeat_password;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email, password,repeat_password', 'required'),
			array('email', 'unique', 'className' => 'User', 'attributeName' => 'email'),
			array('is_confirmed', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('email', 'email'),
			array('password', 'length', 'max'=>40),
			array('password', 'compare', 'compareAttribute'=>'repeat_password'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, email, is_confirmed, dt_added, password', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'email' => 'Email',
			'is_confirmed' => 'Is Confirmed',
			'dt_added' => 'Dt Added',
			'password' => 'Password',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('is_confirmed',$this->is_confirmed);
		$criteria->compare('dt_added',$this->dt_added,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}