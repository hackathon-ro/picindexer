<?php 

class FacebookAccount extends Account {
	protected function beforeValidate() {
		$this->type = 'facebook';
		return parent::beforeValidate();
	}
}