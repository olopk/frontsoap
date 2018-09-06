<?php

$db['app']['driver']='mysql';              // application db driver name
$db['app']['host']='localhost';            // application db hostname
$db['app']['name']='aplikacja';            // application db database name
$db['app']['user']='root';                 // application db user name
$db['app']['pass']='root';                 // application db password


$db['src']['driver']='dblib';              // source db driver name
$db['src']['host']='192.168.1.104:1433';   // source db hostname
$db['src']['name']='UBOJNIA_Sp__ka_jawna'; // source db database name
$db['src']['user']='sa';                   // source db user name
$db['src']['pass']='';                     // source db password

$wsdl = 'http://localhost:4567/wsdl.xml';  // wsdl source