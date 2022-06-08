<?php
include_once("libs/Rapido-Server/index.php");

Sessions
BodyParser
Routes
Layout
DotEnv

use rapido\{Router,Routes,Sessions,BodyParser,Datastorage,Layout,DotEnv,SQLI,Fetch};

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: application/json; text/plain;');

$App = new Router($_SERVER);
$GLOBAL["App"] = $App;
$GLOBAL["Datastorage"] = Datastorage::class;

$App -> use( Sessions::class , './sessions' );
$App -> use( BodyParser::class );
$App -> use( Routes::class , array('./routes' => array(
  "/" => "index",
  "/users" => "users",
)));
$App -> use( Layout::class , "./layout" );
$App -> use( DotEnv::class , "./bestDeal.env");


$App -> handle();
?>
