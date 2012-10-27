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
		$code = Yii::app()->request->getQuery('code', false); // $_GET['code']
		$error = Yii::app()->request->getQuery('error', false);
		$error_reason = Yii::app()->request->getQuery('error_reason', false);
		$error_description = Yii::app()->request->getQuery('error_description', false);
		
		// First, check for permissions and redirect to authorization dialog
		$fb = Yii::app()->facebook;
		if(!$code) {
			if($error_reason == 'user_denied') {
				// User clicked 'cancel'
				Yii::app()->user->setFlash('error', 'The application needs certain permissions to work properly, which you haven\'t granted. Failed adding account');
				
				$this->redirect(array('addAccount'));
			}
			
			// Check if other errors occured
			if($error)
				throw new ServiceException('facebook', $error, $error_reason, $error_description);
			
			// No errors, no code, no token. Go out and get it
			$this->redirect($fb->getLoginUrl(array(
				'scope' => array('user_photos', 'friends_photos'),
				'redirect_uri' => $this->createAbsoluteUrl('addAccount', array('type'=>'facebook')),
			)));
		}
		
		$token = $fb->getAccessToken();
		
		// If account was already added, skip adding in locally
		if(Account::model()->exists('access_token = :at', array(':at' => $token))) {
			Yii::app()->user->setFlash('warning', 'This account was already added');
			$this->redirect(array('accounts'));
		}
		
		// All is well. Add account to local DB
		$model = new FacebookAccount;
		$model->user_id = Yii::app()->user->id;
		$model->access_token = $token;
		
		// Le validation is implicit
		if(!$model->save()) {
			Yii::app()->user->setFlash('error', 'Unable to save account locally.');
			Yii::log(CVarDumper::dumpAsString($model->errors), 'error');
		} else
			Yii::app()->user->setFlash('success', 'Facebook account connected');
		
		$this->redirect(array('accounts'));
	}
	
	protected function addInstagramAccount() {
		// TODO
		$this->redirect(array('accounts'));
	}
}