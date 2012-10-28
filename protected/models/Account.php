<?php

/**
 * This is the model class for table "accounts".
 *
 * The followings are the available columns in table 'accounts':
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $access_token
 */
class Account extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Account the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	private $_typedAccount = null;
	/**
	 * Returns a typed instance of this AR.
	 * E.g. if type == 'facebook', it returns a FacebookAccount
	 */
	protected function getTypedAccount() {
		$classname = ucfirst(strtolower($this->type)).'Account';
		if(class_exists($classname)) {
			$miniMe = $classname::model($classname)->findByPk($this->id);
			if($miniMe) {
				$this->_typedAccount = $miniMe;
			}
		}
		
		return $this->_typedAccount;
	}
	
	public function __call($name, $args=array()) {
		$miniMe = $this->getTypedAccount();
		if($miniMe && is_callable(array($miniMe, $name))) {
			return call_user_func_array(array($miniMe, $name), $args);
		}
		
		return parent::__call($name, $args);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'accounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, type, access_token', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>16),
			array('access_token', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, type, access_token', 'safe', 'on'=>'search'),
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
			'jobs' => array(self::HAS_ONE, 'DelayedJob', 'account_id'),
			'photos' => array(self::HAS_MANY, 'Photo', 'account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'type' => 'Type',
			'access_token' => 'Access Token',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('access_token',$this->access_token,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function afterSave() {
		parent::afterSave();
		
		if(!$this->addDelayedJob()) {
			Yii::log('error', 'Unable to add delayed job for account '.$this->id);
		}
	}
	
	protected function beforeDelete() {
		if($this->job->delete()) {
			$this->addError('job', 'Unable to delete delayed job: '.CVarDumper::dumpAsString($this->job->errors));
			return false;
		}
		return parent::beforeDelete();
	}
	
	public function getName() {
		return $this->__call('getName', array());
	}
	
	public function getEditUrl() {
		// TODO: URL destination page doesn't exist yet
		return Yii::app()->createUrl('/app/default/editAccount', array('id'=>$this->id));
	}
	
	public function getTypeIcon() {
		// TODO: create AccountHelper which generates this
		return $this->type;
	}
	
	public function addDelayedJob() {
		return $this->getTypedAccount()->addDelayedJob();
	}
}