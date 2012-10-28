<?php 

class FacebookPhoto extends Photo {
	protected function beforeValidate() {
		$this->type = 'facebook';
		return parent::beforeValidate();
	}
}