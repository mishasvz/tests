<?php

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',
    'theme'=>'bootstrap',
    'sourceLanguage'=>'ru',
    
	'aliases'=>array(
        'bootstrap'=>realpath(__DIR__.'/../extensions/bootstrap'),
    ),
    
	'preload'=>array(
        'log'
    ),

	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			'ipFilters'=>array(
                '127.0.0.1',
                '::1'
            ),
            'generatorPaths'=>array(
                'bootstrap.gii'
            ),
		),
		
	),
    'behaviors' => array(
        'onBeginRequest'=>array(
            'class'=>'application.components.urlManager.LanguageBehavior'
        ),
    ),

    'components'=>array(
        'config'=>array(
            'class'=>'application.components.DbConfig',
        ),
        
		'user'=>array(
			'allowAutoLogin'=>true,
		),
		'urlManager' => array(
            'class'          => 'application.components.urlManager.LangUrlManager',
            'languageInPath' => true,
            'langParam'      => 'language',
            'urlFormat'      => 'path',
            'showScriptName' => false,
            'rules'          => array(
                ''                                                   => '/site/index',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>'          => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>'                       => '<module>/<controller>/index',
                '<controller:\w+>/<action:\w+>'                       => '<controller>/<action>',
                '<controller:\w+>'                                    => '<controller>/index',
            ),
        ),
        'request' => array(
            'class'                  => 'HttpRequest',
            'enableCsrfValidation'   => true,
            'csrfTokenName'          => 'NAME_TOKENZ',
            'noCsrfValidationRoutes' => array(),
            'enableCookieValidation' => true, 
        ),
		'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=testloc',
            'emulatePrepare' => true,
            'username' => 'testloc',
            'password' => 'testloc',
            'charset' => 'utf8',
            'tablePrefix'=>'tbl_',
        ),
        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CDbLogRoute',
                    'connectionID'=>'db',
                    'autoCreateLogTable'=>true,
                    'logTableName'=>'{{log_errors}}',
					'levels'=>'error, warning',
				),
			),
		),

	),
    'params'=>array(
        'availableLanguages'=>'ru,en',
		'adminEmail'=>'webmaster@example.com',
	),
);
