<?php

$db   = "appscirc_appscentral";
$user = "appscirc_shahid";
$pass = "d%p#@pae";

$db_prefix = "appscirc_";

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

return array(

	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Singapore Environment Story',

	'preload'=>array('log', 'phpthumb'),

	// autoloading model and component classes	
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.controllers.*',		
		'application.extensions.phpthumb.*',
	),

	'defaultController'=>'tab',

	// application components

	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        'db' => array(
            'class'            => 'CDbConnection',
            'connectionString' => "mysql:host=localhost;dbname=" . $db_prefix . "neases",
            'emulatePrepare'   => true,
            'username'         => $user,
            'password'         => $pass,
            'charset'          => 'utf8',
        ),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
    				'<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>',    				
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		)
	),

	'modules'=>array(
        // uncomment the following to enable the Gii tool  
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'yii',
            'ipFilters' => ''
        ) 
    ),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),

);