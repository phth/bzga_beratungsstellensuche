<?php

use Bzga\BzgaBeratungsstellensuche\Report\StatusAllowUrlFopenOrCurlReport;

if (!defined('TYPO3')) {
    die('Access denied.');
}

// We check if either curl is installed or allow_url_fopen
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['bzgaberatungsstellensuche'] = [
    StatusAllowUrlFopenOrCurlReport::class,
];
