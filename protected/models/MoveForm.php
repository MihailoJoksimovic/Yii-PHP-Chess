<?php

/**
 * MoveForm class.
 */
class MoveForm extends CFormModel
{
	public $from;
	public $to;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('from, to', 'required'),
			array('from, to', 'match', 'pattern' => '/[a-h]\d/i'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
		);
	}

	
}