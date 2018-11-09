<?php


// Путь до файла с реквизитамми доступа, который выдал Google
$config['key_file_location'] = __DIR__ . '/client_secret_3xxxxxxx8-1dxxxxxxxxxxxxx2.apps.googleusercontent.com.json';

// Лицензионный ключ полученный в сервисе APMinvest
$config['license_key'] = 'xxxx';


$config['metrics'] = [];

// Yesterday. TZ - Moscow
$dt = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
$yesterday = $dt->modify('-1 day')->format('Y-m-d');


// JS ошибки
$config['metrics']['js-error'] = [

    // Настройки для Google Analytics
    'ga' => [
        'view_id' => '14xxxxxx2',
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
        'view_id' => '14xxxxxx2',
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



// Режим отладки, вместо отправки данных в APMInvest, просто выводит их
const APM_DEBUG = false;

// Путь до лог файла
$config['log-file-path'] = __DIR__ . '/log.log';


return $config;