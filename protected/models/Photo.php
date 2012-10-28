<?php

/**
 * This is the model class for table "photos".
 *
 * The followings are the available columns in table 'photos':
 * @property integer $id
 * @property string $type
 * @property integer $remote_id
 * @property string $url
 * @property string $remote_url
 * @property string $description
 * @property integer $account_id
 * @property integer $job_id
 */
class Photo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Photo the static model class
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
		return 'photos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, remote_id, url, remote_url, account_id, job_id', 'required'),
			array('remote_id, account_id, job_id', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>32),
			array('url, remote_url', 'length', 'max'=>1024),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, remote_id, url, remote_url, description, account_id, job_id', 'safe', 'on'=>'search'),
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
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'remote_id' => 'Remote',
			'url' => 'Url',
			'url' => 'Remote Url',
			'description' => 'Description',
			'account_id' => 'Account',
			'job_id' => 'Job',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('remote_id',$this->remote_id);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('remote_url',$this->remote_url,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('job_id',$this->job_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function requestDescription() {
		// TODO
	}
	
	public function receiveDescription($description) {
		$this->description = $description;
		if(!$this->save())
			Yii::log('Unable to receive description for photo '.$this->id, 'error');
	}
}