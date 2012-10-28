<?php 

class FacebookDelayedJob extends DelayedJob {
	protected function beforeValidate() {
		$this->type = 'facebook';
		return parent::beforeValidate();
	}
	
	public function process() {
		// TODO
	}
}