<?php 

class FacebookAccount extends Account {
	protected function beforeValidate() {
		$this->type = 'facebook';
		return parent::beforeValidate();
	}
	
	public function getName() {
		Yii::trace('FacebookAccount::getName');
		if($this->isNewRecord) {
			Yii::log('Trying to get name of new record', 'warning');
			return '';
		}
		
		$fb = Yii::app()->facebook;
		$fb->setAccessToken($this->access_token);
		
		$user = $fb->api('/me');
		
		return $user['name'];
	}
	
	public function addDelayedJob() {
		$job = new FacebookDelayedJob;
		$job->account_id = $this->id;
		$job->job = array(
			'access_token' => $this->access_token,
			'since' => time(),
		);
		
		$ok = $job->save();
		if(!$ok)
			Yii::app()->user->setFlash('error', 'Unable to add job for account. Details: '.CVarDumper::dumpAsString($job->errors));
		return $ok;
	}
}