<?php

$db['driver']='mysql';          // driver name
$db['host']='localhost';        // hostname
$db['name']='aplikacja';        // database name
$db['user']='root';             // user name
$db['pass']='';                 // password


// Database object initalization
$db = new \Database\Database($db['driver'], $db['host'], $db['name'], $db['user'], $db['pass']);

