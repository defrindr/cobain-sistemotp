<?php

use kartik\mpdf\Pdf;

require_once 'Custom.php';

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'K1000HPK',
    'name' => 'K1000HPK',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'id',
    'timeZone' => 'Asia/Jakarta',
    'components' => [
        'pdf' => [
            'class' => Pdf::class,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            // refer settings section for all configuration options
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'secret',
            'parsers' => [
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
            ],
        ],
        'response' => [
            'class' => '\yii\web\Response',
            'on beforeSend' => [
                \app\components\ErrorResponseHelper::class,
                "beforeResponseSend",
            ],
        ],
        'formatter' => [
            'class' => \app\formatter\CustomFormatter::class,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $params['adminEmail.host'],
                'username' => $params['adminEmail.email'],
                'password' => $params['adminEmail.password'],
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => [
                // api route
                'api/v1/<controller:\w+>/<action>' => 'api/v1/<controller>/<action>',
                'api/<controller:\w+>/<action>' => 'api/<controller>/<action>',
                'api/<controller:\w+>/<action>/<id>' => 'api/<controller>/<action>',
                'POST api/<controller:\w+>' => 'api/<controller>/create',
                'PUT,PATCH api/<controller:\w+>/<id>' => 'api/<controller>/update',
                'GET,HEAD api/<controller:\w+>' => 'api/<controller>/index',
                'GET,HEAD api/<controller:\w+>/<id>' => 'api/<controller>/view',

                // web route
                '<controller:[\w\-\_]+>' => '<controller>/index',
                '<controller:[\w\-\_]+>/<action:[\w\-\_]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[\w\-\_]+>/<action:[\w\-\_]+>' => '<controller>/<action>',
            ],
        ],

        'assetManager' => [
            'bundles' => [

                // 'yii2mod\alert\AlertAsset' => [
                //     'css' => [
                //         'dist/sweetalert.css',
                //         'themes/twitter/twitter.css',
                //     ],
                // ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],

            ],
        ],
        'db' => require __DIR__ . '/db.php',
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'giiant-model' => [
                'class' => 'schmunk42\giiant\generators\model\Generator',
                'templates' => [
                    'Annex' => '@app/template/generators/model/default',
                ],
            ],
            'giiant-crud' => [
                'class' => 'schmunk42\giiant\generators\crud\Generator',
                'templates' => [
                    'Annex' => '@app/template/generators/crud/default',
                ],
            ],
        ],
    ];
}

return $config;
