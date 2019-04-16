<?php


// Путь до файла с реквизитамми доступа, который выдал Google
$config['key_file_location'] = __DIR__ . '/client_secret_3xxxxxxx8-1dxxxxxxxxxxxxx2.apps.googleusercontent.com.json';

// Лицензионный ключ полученный в сервисе APMinvest
$config['license_key'] = '5xxxxxxxxxxxxxxxxxxx';


$config['metrics'] = [];

// Yesterday. TZ - Moscow
$dt = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
$yesterday = $dt->modify('-1 day')->format('Y-m-d');

// View ID
$viewId = '2xxxxxxx';


// JS ошибки
$config['metrics']['js-error'] = [

    // Настройки для Google Analytics
    'ga' => [
        'view_id' => $viewId,
        'dimension' => 'ga:eventCategory',
        'event_filter' => 'javascripterror',
        'metric' => 'ga:totalEvents'
    ],

    // Настройки для APMinvest
    'apm' => [
        'license_key' => $config['license_key'],
        'metric_code' => 'js-errors',
        'metric_label' => 'JS ошибки',
        'date' => $yesterday
    ]
];


// Конверсия
$config['metrics']['conversion'] = [

    // Настройки для Google Analytics
    'ga' => [
        'view_id' => $viewId,
        'metric' => 'ga:transactionsPerSession'
    ],

    // Настройки для APMinvest
    'apm' => [
        'license_key' => $config['license_key'],
        'metric_code' => 'conversion',
        'metric_label' => 'Конверсия',
        'date' => $yesterday
    ]
];

// Пользователи за сутки
$config['metrics']['users'] = [

    // Настройки для Google Analytics
    'ga' => [
        'view_id' => $viewId,
        'metric' => 'ga:users'
    ],

    // Настройки для APMinvest
    'apm' => [
        'license_key' => $config['license_key'],
        'metric_code' => 'users',
        'metric_label' => 'Пользователи',
        'date' => $yesterday
    ]
];



// Режим отладки, вместо отправки данных в APMInvest, просто выводит их
const APM_DEBUG = true;

// Путь до лог файла
$config['log-file-path'] = __DIR__ . '/log.log';


return $config;