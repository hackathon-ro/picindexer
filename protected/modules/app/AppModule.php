<?php

class AppModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'app.models.*',
			'app.components.*',
		));
	}
	
	public function getName() {
		return 'Application';
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			
			// Verify if user is logged in
			if(Yii::app()->user->isGuest)
				throw new CHttpException(403, 'You must log in to use the application');
			
			return true;
		}
		else
			return false;
	}
}
