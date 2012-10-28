<?php

/**
 * Main configuration.
 * All properties can be overridden in mode_<mode>.php files
 */

return array(

	// Set yiiPath (relative to Environment.php)
	'yiiPath' => dirname(__FILE__) . '/../../../yii/framework/yii.php',
	'yiicPath' => dirname(__FILE__) . '/../../../yii/framework/yiic.php',
	'yiitPath' => dirname(__FILE__) . '/../../../yii/framework/yiit.php',

	// Set YII_DEBUG and YII_TRACE_LEVEL flags
	'yiiDebug' => true,
	'yiiTraceLevel' => 0,

	// Static function Yii::setPathOfAlias()
	'yiiSetPathOfAlias' => array(
		// uncomment the following to define a path alias
		//'local' => 'path/to/local-folder'
	),

	// This is the main Web application configuration. Any writable
	// CWebApplication properties can be configured here.
	'configWeb' => array(

		'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name' => 'PicIndexer',

		// Preloading 'log' component
		'preload' => array('log','bootstrap'),

		// Autoloading model and component classes
		'import' => array(
			'application.models.*',
			'application.components.*',
		),
		
		// Modules
		'modules' => array(
			'app',
		),
		
		// Application components
		'components' => array(
		
			'user' => array(
				// enable cookie-based authentication
				'allowAutoLogin' => true,
			),
			
			// uncomment the following to enable URLs in path-format
			'urlManager'=>array(
				'urlFormat'=>'path',
				'rules'=>array(
					'<controller:\w+>s'=>'<controller>/index',
					'<module:\w+>/<controller:\w+>s'=>'<module>/<controller>/index',
					'<controller:\w+>/<id:\d+>'=>'<controller>/view',
					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				),
				'showScriptName' => false,
			),

			// Database
			'db' => array(
				'connectionString' => '', //override in config/mode_<mode>.php
				'emulatePrepare' => true,
				'username' => '', //override in config/mode_<mode>.php
				'password' => '', //override in config/mode_<mode>.php
				'charset' => 'utf8',
			),

			// Error handler
			'errorHandler'=>array(
				// use 'site/error' action to display errors
				'errorAction'=>'site/error',
			),
			
			// Yii-Bootstrap
			'bootstrap'=>array(
				'class'=>'ext.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
			),
			
			// Facebook API
			'facebook'=>array(
				'class' => 'ext.yii-facebook-opengraph.SFacebook',
				'appId'=>'YOUR_FACEBOOK_APP_ID', // needed for JS SDK, Social Plugins and PHP SDK
				'secret'=>'YOUR_FACEBOOK_APP_SECRET', // needed for the PHP SDK
				//'fileUpload'=>false, // needed to support API POST requests which send files
				//'trustForwarded'=>false, // trust HTTP_X_FORWARDED_* headers ?
				//'locale'=>'en_US', // override locale setting (defaults to en_US)
				//'jsSdk'=>true, // don't include JS SDK
				//'async'=>true, // load JS SDK asynchronously
				//'jsCallback'=>false, // declare if you are going to be inserting any JS callbacks to the async JS SDK loader
				//'status'=>true, // JS SDK - check login status
				//'cookie'=>true, // JS SDK - enable cookies to allow the server to access the session
				//'oauth'=>true,  // JS SDK - enable OAuth 2.0
				//'xfbml'=>true,  // JS SDK - parse XFBML / html5 Social Plugins
				//'frictionlessRequests'=>true, // JS SDK - enable frictionless requests for request dialogs
				//'html5'=>true,  // use html5 Social Plugins instead of XFBML
				//'ogTags'=>array(  // set default OG tags
					//'title'=>'MY_WEBSITE_NAME',
					//'description'=>'MY_WEBSITE_DESCRIPTION',
					//'image'=>'URL_TO_WEBSITE_LOGO',
				//),
			),

		),

		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>array(
			// this is used in contact page
			'adminEmail'=>'webmaster@example.com',
		),

	),

	// This is the Console application configuration. Any writable
	// CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
	'configConsole' => array(

		'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name' => 'PicIndexer',

		// Preloading 'log' component
		'preload' => array('log'),

		// Autoloading model and component classes
		'import'=>'inherit',

		// Application componentshome
		'components'=>array(

			// Database
			'db'=>'inherit',

			// Application Log
			'log' => array(
				'class' => 'CLogRouter',
				'routes' => array(
					// Save log messages on file
					array(
						'class' => 'CFileLogRoute',
						'levels' => 'error, warning, trace, info',
					),
				),
			),

		),

	),

);