<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Засекаем время
$time_start = microtime(true);

require_once '../config/config.php';
require_once '../classes/Func.php';
require_once '../classes/System.php';
$site = new System();

session_start();
$result = "error";
if($site->request->isAJAX() and $site->admins->login() ) {
	$module = $site->request->get('module', 'string');
	$module = preg_replace("/[^-_\.A-Za-z0-9]+/", "", $module);
	$action = $site->request->get('action', 'string');
	$action = preg_replace("/[^-_\.A-Za-z0-9]+/", "", $action);
	$id = $site->request->get('id', 'integer');
	$id2 = $site->request->get('id2', 'integer');
        $id3 = $site->request->get('id3', 'integer');
        
	if($module and $site->admins->get_level_access($module)==2 and $id and $action) {
		$result = $site->$module->$action($id, $id2, $id3);
	}
}

echo $result;