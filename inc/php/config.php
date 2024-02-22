<?php
ob_start();
session_start();


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
//This is for CSRF
$key = 'assets_2022_'.$_SERVER['REMOTE_ADDR'];
$md5String = md5($key);
//$md5String = $key;

$supplierTypeArray = array('1'=>'supplier', '2'=>'manufacturer', '3'=>'service_provider', '4'=>'vendor');

$renewalDurationArray = array('monthly','quarterly','half_yearly','yearly');

?>