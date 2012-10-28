<?php

class JobsCommand extends CConsoleCommand
{
	public function init() {
		//
	}
	
	public function actionProcess() {
		$i = 10;
		while($i--) {
			$job = DelayedJob::model()->new()->find();
			if($job)
				$job->process();
			else
				break;
		}
	}
}