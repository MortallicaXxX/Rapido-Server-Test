<?php
$Router = $GLOBALS['App'];
$GLOBALS['chanel'] = "/";

// TODO GET , POST , PUT , DELETE is usable  CONNECT , HEAD not yet.

// Serve index.html on the index chanel
$Router -> get("/",function($req , $res){
  $res -> send("./public/index.html","file");
});


$GLOBALS['chanel'] = null;
?>
