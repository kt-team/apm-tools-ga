<?php

/**
 * @param string $keyFileLocation
 * @return Google_Service_AnalyticsReporting
 * @throws Google_Exception
 */
function initializeAnalytics($keyFileLocation)
{
    $client = new Google_Client();
    $client->setApplicationName("AMP");
    $client->setAuthConfig($keyFileLocation);
    $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
    $analytics = new Google_Service_AnalyticsReporting($client);

    return $analytics;
}

/**
 * @param Google_Service_AnalyticsReporting $analytics
 * @param array $reportSettings
 * @return mixed
 */
function getReport(Google_Service_AnalyticsReporting $analytics, array $reportSettings)
{
    $startDate = isset($reportData['start_date']) ? $reportSettings['start_date'] : 'yesterday';
    $endDate = isset($reportData['end_date']) ? $reportSettings['end_date'] : 'yesterday';

    // Create the DateRange object.
    $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    $dateRange->setStartDate($startDate);
    $dateRange->setEndDate($endDate);

    if(!empty($reportSettings['metric'])) {
        // Create the Metrics object.
        $sessions = new Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression($reportSettings['metric']);
    }

    if(!empty($reportSettings['dimension'])) {
        $dimensions = new Google_Service_AnalyticsReporting_Dimension();
        $dimensions->setName($reportSettings['dimension']);

        if(!empty($reportSettings['event_filter'])) {
            // Create the DimensionFilter.
            $dimensionFilter = new Google_Service_AnalyticsReporting_DimensionFilter();
            $dimensionFilter->setDimensionName($reportSettings['dimension']);
            $dimensionFilter->setOperator('EXACT');
            $dimensionFilter->setExpressions([$reportSettings['event_filter']]);

            // Create the DimensionFilterClauses
            $dimensionFilterClause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
            $dimensionFilterClause->setFilters(array($dimensionFilter));
        }
    }

    // Create the ReportRequest object.
    $request = new Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId($reportSettings['view_id']);
    $request->setDateRanges($dateRange);
    if(!empty($reportSettings['metric'])) {
        $request->setMetrics([$sessions]);
    }
    if(!empty($reportSettings['dimension'])) {
        $request->setDimensions([$dimensions]);
        if(!empty($reportSettings['event_filter'])) {
            $request->setDimensionFilterClauses([$dimensionFilterClause]);
        }
    }

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests([$request]);

    return $analytics->reports->batchGet($body);
}

/**
 * @param Google_Service_AnalyticsReporting_GetReportsResponse $reports
 * @return int
 * @throws Exception
 */
function getResult(Google_Service_AnalyticsReporting_GetReportsResponse $reports)
{
    $result = false;
    if (!empty($reports[0])) {
        $rows = $reports[0]->getData()->getRows();
        if (!empty($rows[0])) {
            $metrics = $rows[0]->getMetrics();
            if (!empty($metrics[0])) {
                $values = $metrics[0]->getValues();
                if (!empty($values[0])) {
                    $result = $values[0];
                }
            }
        } else {
            $result = 0;
        }
    }

    if ($result === false) {
        throw new \Exception('Не удалось получить данные');
    }

    return $result;
}

/**
 * @param int $metric
 * @param array $apmSettings
 * @throws Exception
 */
function sendToApm($metric, array $apmSettings)
{
    $url = 'https://service.apminvest.com/metrics/stat/' . $apmSettings['metric_code'];

    $params = [
        'metrics' => $metric,
        'date' => $apmSettings['date'],
        'key' => $apmSettings['license_key'],
        'label' => $apmSettings['metric_label'],
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    if($info['http_code'] != 200 || !$output) {
        throw new \Exception('К сожалению, не удалось отправить матрики в APMInvest. Проверьте, пожалуйста, параметры или обратитесь в тех. поддержку.');
    }
}


/**
 * @param $msg
 */
function _log($msg) {
    global $config;
    $date = date('Y-m-d H:i:s');
    $msg = $date . ' - ' . $msg . PHP_EOL;
    if(!file_put_contents($config['log-file-path'], $msg, FILE_APPEND)) {
        echo $date . ' - Error file log';
    }
}
