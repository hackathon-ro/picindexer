<?php

/**
 * This is the model class for table "delayed_jobs".
 *
 * The followings are the available columns in table 'delayed_jobs':
 * @property integer $id
 * @property integer $account_id
 * @property string $type
 * @property string $content
 * @property string $status
 */
class DelayedJob extends CActiveRecord
{
	private $_content = array();
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DelayedJob the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	private $_typedDelayedJob = null;
	/**
	 * Returns a typed instance of this AR.
	 * E.g. if type == 'facebook', it returns a FacebookDelayedJob
	 */
	protected function getTypedDelayedJob() {
		$classname = ucfirst(strtolower($this->type)).'DelayedJob';
		Yii::import('application.models.'.$classname);
		if(class_exists($classname)) {
			
			$miniMe = call_user_func(array($classname, 'model'), $classname);
			$miniMe = $miniMe->findByPk($this->id);
			if($miniMe) {
				$this->_typedDelayedJob = $miniMe;
			}
		}
		
		return $this->_typedDelayedJob;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'delayed_jobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, type', 'required'),
			array('account_id', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>16),
			array('status', 'length', 'max'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_id, type, content, status', 'safe', 'on'=>'search'),
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
	
	public function scopes() {
		return array(
			'new' => array('condition'=>'status="new"'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_id' => 'Account',
			'type' => 'Type',
			'content' => 'Content',
			'status' => 'Status',
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
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getType() {
		return $this->_content['type'];
	}
	
	public function setType($type) {
		$this->_content['type'] = $type;
	}
	
	public function getJob() {
		return $this->_content['job'];
	}
	
	public function setJob($job) {
		$this->_content['job'] = $job;
	}
	
	protected function afterFind() {
		$this->_content = CJSON::decode($this->content);
		$this->type = $this->_content['type'];
	}
	
	protected function beforeSave() {
		if(!parent::beforeSave())
			return false;
		$this->content = CJSON::encode($this->_content);
		return true;
	}
	
	protected function startProcessing() {
		$this->status = 'started';
		if(!$this->save()) {
			Yii::log('Unable to start job '.$this->id.': '.CVarDumper::dumpAsString($this->errors));
			throw new CException('Unable to start job');
		}
	}
	
	public function process() {
		$this->startProcessing();
		$ok = $this->getTypedDelayedJob()->process();
		$this->endProcessing();
		return $ok;
	}
	
	protected function endProcessing() {
		$this->status = 'done';
		if(!$this->save()) {
			Yii::log('Unable to end job '.$this->id.': '.CVarDumper::dumpAsString($this->errors));
			throw new CException('Unable to end job');
		}
	}
}