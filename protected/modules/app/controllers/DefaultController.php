<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	/**
	 * Edit accounts
	 */
	public function actionAccounts() {
		$user = User::model()->findByPk(Yii::app()->user->id);
		$accounts = $user->accounts;
		
		$this->render('accounts', array(
			'accounts' => $accounts,
		));
	}
	
	public function actionAddAccount($type = false) {
		switch($type) {
			case 'facebook':
				$this->addFacebookAccount();
				break;
			case 'instagram':
				$this->addInstagramAccount();
				break;
		}
		
		$accountTypes = array(
			array('label' => 'Facebook', 'url' => array('addAccount', 'type'=>'facebook') ),
			array('label' => 'Instagram', 'url' => array('addAccount', 'type'=>'instagram') ),
		);
		
		$this->render('addAccount', array(
			'accountTypes' => $accountTypes,
		));
	}
	
	protected function addFacebookAccount() {
		// TODO
		$this->redirect(array('accounts'));
	}
	
	protected function addInstagramAccount() {
		// TODO
		$this->redirect(array('accounts'));
	}
}