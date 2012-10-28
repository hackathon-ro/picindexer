<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$model = new SearchForm;
		
		$results = false;
		if(isset($_POST['SearchForm'])) {
			$model->attributes = $_POST['SearchForm'];
			if($model->validate())
				$results = $model->search();
		}
		
		$this->render('index', array(
			'model'=>$model,
			'results'=>$results,
		));
	}
}