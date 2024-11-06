<?php

use app\src\Application\Config\Config;
use app\src\Common\Databases\MySQL;
use app\src\Common\Loggers\Logger;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'requestContext' => [
            'class' => 'app\components\RequestContext',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'oqdWn6aT_icKRWyxYjOSZ1oM0n9g8mBn',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
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
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'api/v1/role',
                    ],
                    'tokens' => [
                        '{id}' => '<id:[A-Za-z0-9-]+>',
                    ],
                ],
            ],
        ],
    ],
    'container' => [
        'singletons' => [
            'app\src\Common\Loggers\Logger' => function () {
                return new Logger("APP");
            },
            'app\src\Application\Config\Config' => function () {
                return new Config();
            },
            'app\src\Common\Databases\MySQL' => [
                'class' => 'app\src\Common\Databases\MySQL'
            ],
            'app\src\Application\Usecases\RoleUsecase' => [
                'class' => 'app\src\Application\Usecases\RoleUsecaseImpl',
            ],
            'app\src\Infrastructure\Repository\MySQL\RoleRepository' => [
                'class' => 'app\src\Infrastructure\Repository\MySQL\RoleRepositoryImpl',
            ]
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
