<?php 

class FacebookDelayedJob extends DelayedJob {
	protected function beforeValidate() {
		$this->type = 'facebook';
		return parent::beforeValidate();
	}
	
	public function process() {
		$fb = Yii::app()->facebook;
		$fb->setAccessToken($this->account->access_token);
		
		$fql = CJSON::ENCODE(array(
			'photonotifications' => sprintf(
				'SELECT object_id FROM notification WHERE recipient_id = me() AND created_time > "%s" AND object_type = "photo"',
				date('c', strtotime("-2 days"))
			),
			'result' => sprintf('SELECT src, object_id FROM photo WHERE object_id IN (SELECT object_id FROM #photonotifications)'),
		));
		
		$photos = $fb->api(array(
			'method'=>'fql.multiquery',
			'queries'=>$fql,
		));
		
		if(!isset($photos[1]) || !isset($photos[1]['fql_result_set']))
			throw new CException('Malformed response');
		
		$photos = $photos[1]['fql_result_set'];
		
		if(empty($photos))
			throw new CException('No photos found');
		
		foreach($photos as $p) {
			$localphoto = new FacebookPhoto;
			$localphoto->url = $p['src'];
			$localphoto->remote_id = $p['object_id'];
			$localphoto->job_id = $this->id;
			$localphoto->account_id = $this->account_id;
			
			if(!$localphoto->save())
				Yii::log('Unable to save Facebook photo '.$p['object_id'].': '.CVarDumper::dumpAsString($localphoto->errors), 'warning');
			else {
				$localphoto->requestDescription();
			}
		}
	}
}