<?php

class AccountController extends Controller
{
	public function actionAdd($type = false) {
		switch($type) {
			case 'facebook':
				$this->addFacebookAccount();
				break;
			case 'instagram':
				$this->addInstagramAccount();
				break;
		}
		
		$accountTypes = array(
			array('label' => 'Facebook', 'url' => array('add', 'type'=>'facebook') ),
			array('label' => 'Instagram', 'url' => array('add', 'type'=>'instagram') ),
		);
		
		$this->render('add', array(
			'accountTypes' => $accountTypes,
		));
	}

	public function actionDelete($id)
	{
		$account = Account::model()->findByPk($id);
		if(!$account) {
			Yii::app()->user->setFlash('error', 'Invalid account ID provided');
			if(!Yii::app()->request->isAjaxRequest)
				$this->redirect('index');
			else
				Yii::app()->end();
		}
		if($account->user_id != Yii::app()->user->id)
			throw new CHttpException(403, 'Unauthorized to delete that account');
		
		if($account->delete())
			Yii::app()->user->setFlash('success', 'Account deleted');
		else
			Yii::app()->user->setFlash('error', 'Unable to delete account');
		
		if(!Yii::app()->request->isAjaxRequest)
			$this->redirect('index');
	}

	public function actionIndex()
	{
		$user = User::model()->findByPk(Yii::app()->user->id);
		$accounts = $user->accounts;
		
		$this->render('index', array(
			'accounts' => $accounts,
		));
	}

	public function actionUpdate()
	{
		$this->render('update');
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
				
				$this->redirect(array('add'));
			}
			
			// Check if other errors occured
			if($error)
				throw new ServiceException('facebook', $error, $error_reason, $error_description);
			
			// No errors, no code, no token. Go out and get it
			$this->redirect($fb->getLoginUrl(array(
				'scope' => array('user_photos', 'friends_photos'),
				'redirect_uri' => $this->createAbsoluteUrl('add', array('type'=>'facebook')),
			)));
		}
		
		$token = $fb->getAccessToken();
		
		// If account was already added, skip adding in locally
		if(Account::model()->exists('access_token = :at', array(':at' => $token))) {
			Yii::app()->user->setFlash('warning', 'This account was already added');
			$this->redirect(array('index'));
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
		
		$this->redirect(array('index'));
	}
	
	protected function addInstagramAccount() {
		// TODO
		$this->redirect(array('index'));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}