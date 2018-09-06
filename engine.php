<?php
require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/class/Database.php');
require_once(__DIR__ . '/class/Engine.php');

header('Content-Type: text/html; charset=utf-8');
ini_set('max_execution_time', 0);

try {
    $dbs = new \Database\Database($db['src']['driver'], $db['src']['host'], $db['src']['name'], $db['src']['user'], $db['src']['pass']);        // Database source
    $db = new \Database\Database($db['app']['driver'], $db['app']['host'], $db['app']['name'], $db['app']['user'], $db['app']['pass']);
}
catch(PDOException $e) {
    echo "Exception: " . $e->getMessage();
}

$engine = new \Engine\Engine($dbs, $db, $wsdl);
$engine->process();


