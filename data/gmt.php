<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//require '../configs.php';

//var_export($_GET);


$date = empty($_GET['date']) || !isDate($_GET['date']) ? date('d-m-Y') : $_GET['date'];
$hours = empty($_GET['hours']) || $_GET['hours'] == 'undefined' ? date('H') : $_GET['hours'];
$minutes = empty($_GET['Minutes']) || $_GET['Minutes'] == 'undefined' ? date('i') : $_GET['Minutes'];
$timezone = $_GET['timezone'];

if ((!$timezone) || (!$hours) || (!$minutes) || (!$date)) exit("UTC");

if ($minutes < 0) $minutes = 0;
if ($minutes > 59) $minutes = 0;

$date = $date.' '.$hours.':'.$minutes;
//echo $date;

date_default_timezone_set($timezone);
$dt = new DateTime($date);
$dt = $dt->format('P');

echo str_replace('+0', '+', $dt);



