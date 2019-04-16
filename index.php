<?php

require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/config.php';

require_once __DIR__ . '/apm.php';


if(APM_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}


// GA JS error
if(!empty($config['metrics']['js-error'])) {
    try {
        _log('GA JS errors start');

        // Initialize Analytics
        $analytics = initializeAnalytics($config['key_file_location']);


        // JS Report
        $jsResponse = getReport($analytics, $config['metrics']['js-error']['ga']);
        $jsResult = getResult($jsResponse);

        // Debug or Send
        if (APM_DEBUG) {
            echo 'JS errors: ' . $jsResult . "\n";
        } else {
            sendToApm($jsResult, $config['metrics']['js-error']['apm']);
            echo 'Done';
        }

        _log('GA JS errors result: ' . $jsResult);

    } catch (\Exception $e) {
        _log('GA JS - ERROR!!! ' . $e->getMessage());
        echo $e->getMessage();
    }
}


// --------


// GA Conversion
if(!empty($config['metrics']['conversion'])) {
    try {
        _log('GA Conversion start');

        // Conversion Report
        $convResponse = getReport($analytics, $config['metrics']['conversion']['ga']);

        $convResult = getResult($convResponse);
        $convResult = round($convResult, 2);

        // Debug or Send
        if (APM_DEBUG) {
            echo 'Conversion: ' . $convResult . "\n";
        } else {
            sendToApm($convResult, $config['metrics']['conversion']['apm']);
            echo 'Done';
        }

        _log('GA Conversion result: ' . $convResult);
    } catch (\Exception $e) {
        _log('GA Conversion - ERROR!!! ' . $e->getMessage());
        echo $e->getMessage();
    }
}

// --------


// GA Users
if(!empty($config['metrics']['users'])) {
    try {
        _log('GA Users start');

        // Users Report
        $usersResponse = getReport($analytics, $config['metrics']['users']['ga']);

        $usersResult = getResult($usersResponse);
        $usersResult = round($usersResult, 2);

        // Debug or Send
        if (APM_DEBUG) {
            echo 'Users: ' . $usersResult . "\n";
        } else {
            sendToApm($usersResult, $config['metrics']['users']['apm']);
            echo 'Done';
        }

        _log('GA Users result: ' . $usersResult);
    } catch (\Exception $e) {
        _log('GA Users - ERROR!!! ' . $e->getMessage());
        echo $e->getMessage();
    }
}