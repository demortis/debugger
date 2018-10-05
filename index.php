<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 05.09.18
 * Time: 15:16
 */
//

include_once 'Debugger.php';

//include_once ('core/SqlDebugger.php');
//
//include_once ('core/SqlQueryParser.php');

ini_set('xdebug.max_nesting_level', 100);
ini_set('xdebug.var_display_max_depth', 10);

$connection = mysqli_connect('localhost', 'root', 'aq1sw2de3');

$connection->select_db('brinex');

$query = 'SELECT * FROM `Template` WHERE `Template_ID` = \'185\'';

$arguments = [
    'query' => $query,
    'connection' => $connection
];

$debugger = \debugger\Debugger::sql($arguments);

//$result = $debugger->getResult();

//$query = 'SELECT Message_ID, Price1, bonuspercent FROM Message441 WHERE (StockUnitsNashi>0 || MagazinUnits>0) AND PriceSel=0 AND Store=231';
////
//$arguments = [
//    'query' => $query,
//    'connection' => $connection
//];
//
//$debugger = \debugger\Debugger::sql($arguments);
//
//$query = 'SET NAMES utf-8';
//
//$arguments = [
//    'query' => $query,
//    'connection' => $connection
//];
//
//$debugger = \debugger\Debugger::sql($arguments);
////var_dump($debugger);die;

//$query = 'SELECT Message_ID, Price1, bonuspercent FROM Message441 WHERE (StockUnitsNashi>0 || MagazinUnits>0) AND PriceSel=0 AND Store=231';
//
//$parser = new SqlQueryParser($query);

echo \debugger\Debugger::getPanel();