<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE PATCH');
session_start();
require_once('classes/Api.php');

$api = new Api();

$response = $api->setEndPoint($_REQUEST['endPoint'])->setMethod($_REQUEST['method'] ?? 'GET')
    ->setAccessToken($_SESSION['access_token'])
    ->setParams($_REQUEST['params'])
    ->callApi()
    ->sendResponse();
