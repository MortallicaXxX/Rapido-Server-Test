<?php
include_once("libs/Rapido-Server/rapido.php");
include_once("middlewares/Rapido@Routes/index.php");
include_once("middlewares/Rapido@BodyParser/index.php");
include_once("middlewares/Rapido@Layout/index.php");
include_once("middlewares/Rapido@DotEnv/index.php");
include_once("middlewares/Rapido@Sessions/index.php");

use rapido\{Router,SQLI,Fetch};

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: application/json; text/plain;');

$App = new Router($_SERVER);
$GLOBAL["App"] = $App;
// $GLOBAL["Datastorage"] = Datastorage::class;

$App -> use( Sessions::class , './sessions' );
$App -> use( BodyParser::class );
$App -> use( Routes::class , array('./routes' => array(
  "/" => "index",
  "/users" => "users",
)));
$App -> use( Layout::class , "./layout" );
$App -> use( DotEnv::class , "./bestDeal.env");

$App -> get("/test",function($req , $res){
  $res -> send("test","text");
});


$App -> handle();
?>
