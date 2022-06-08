<?php

namespace Router{

  interface IRequest{

  }

  interface IResponse{

    public function send(String $data,String $option);
    public function sendText(string $text);
    public function sendJSON(array $arrayJson);
    public function sendFile(string $pathToFile);
    public function toDownload(string $pathToFile);

  }

  /**
    *Name : Router
    *Type : Class
    *Description :
    *Use-case :
    *Sample :
  */
  class Request implements IRequest{
    function __construct($router){
      foreach (array_keys($router) as $key) {
        $this->{$key} =  $router[$key];
      }
    }
  }

  /**
    *Name : Router
    *Type : Class
    *Description :
    *Use-case :
    *Sample :
  */
  class Response implements IResponse{
    /**
      *Description :
    */
    public function send($data,$option = "text"){
      if($option == "text")$this -> sendText($data);
      else if($option == "json")$this -> sendJSON($data);
      else if($option == "file")$this -> sendFile($data);
      else if($option == "ddl")$this -> toDownload($data);
      else throw "option not correct";
    }

    /**
      *Description :
    */
    public function sendText($text){
      header("Content-Type: text/plain; charset=UTF-8");
      echo $text;
    }

    /**
      *Description :
    */
    public function sendJSON($arrayJson){
      header('Content-Type: application/json');
      echo json_encode($arrayJson);
    }

    /**
      *Description :
    */
    public function sendFile($pathToFile){
      if (file_exists($pathToFile)) {
        echo file_get_contents($pathToFile);
      }
    }

    public function toDownload($pathToFile){
      if (file_exists($pathToFile)) {
          header('Content-Description: File Transfer');
          header('Content-Type: application/octet-stream');
          header('Content-Disposition: attachment; filename="'.basename($pathToFile).'"');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header('Content-Length: ' . filesize($pathToFile));
          var_dump(readfile($pathToFile));
          exit;
      }
    }
  }

  /**
    *Name : Router
    *Type : Class
    *Description :
    *Use-case :
    *Sample :
  */
  class Router{

    public $routeur;
    private $_get = array(); /** @author contient la liste des get */
    private $_post = array(); /** @desc contient la liste des post */
    private $_middleware = array();

    function __construct($_server){
      $this -> routeur = $_server;
    }

    /**
      *Description :
    */
    function handle(){
      $this -> __handle_navigate();
      $this -> __handle_middleware();
      if ($this -> routeur["REQUEST_METHOD"] == "GET") {
        $this -> __delegate_get();
      }
      if ($this -> routeur["REQUEST_METHOD"] == "POST") {
        $this -> __delegate_post();
      }
    }

    /**
      *Description :
    */
    private function __handle_navigate(){
      if (!function_exists('str_contains')) {
          function str_contains(string $haystack, string $needle): bool
          {
              return '' === $needle || false !== strpos($haystack, $needle);
          }
          if(str_contains($this -> routeur["REQUEST_URI"], "chanel") == false)header('Location: index.php?chanel=/');
      }
      else if(str_contains($this -> routeur["REQUEST_URI"], "chanel") == false)header('Location: index.php?chanel=/');
    }

    /**
      *Description :
    */
    private function __handle_middleware(){
      foreach ($this -> _middleware as $middleware) {
        foreach ($middleware as $name => $instance) {
          $this -> routeur = $instance -> Program($this -> routeur);
        }
      }
    }

    /**
      *Description :
    */
    private function __get_chanel(){
      $query = explode("&", $this -> routeur["QUERY_STRING"]);
      foreach ($query as $word) {
        $r = explode("=", $this -> routeur["QUERY_STRING"]);
        if($r[0] == "chanel")return $r[1];
      }
    }

    /**
      *Description :
    */
    private function __open_chanel($method){
      $chanel = $this -> __get_chanel();
      $primaryChanel = $this -> __primary_chanel($chanel);
      $secondaryChanel = $this -> __secondary_chanel($chanel);

      if(key_exists($primaryChanel,$method) == true && key_exists($secondaryChanel,$method[$primaryChanel]) == true)return $method[$primaryChanel][$secondaryChanel];
      else echo "<p>chanel not fund<p>";
    }

    /**
      *Description :
    */
    private function __delegate_get(){
      $chanelCallBack = $this -> __open_chanel($this -> _get); // permet de charger le chanel
      $chanelCallBack(new Request($this -> routeur),new Response());
    }

    /**
      *Description :
    */
    private function __delegate_post(){
      $chanelCallBack = $this -> __open_chanel($this -> _post); // permet de charger le chanel
      $chanelCallBack(new Request($this -> routeur),new Response());
    }

    /**
      *Description : Permet de connaitre le chanel de base
      *Exemple : "localhost/test/blou/blou" = "test"
    */
    private function __primary_chanel($chanel){
      $expl = explode("/", $chanel);
      return (count($expl) > 1 ? $expl[1] : $expl[0]);
    }

    /**
      *Description : Permet de connaitre le chanel de base
      *Exemple : "localhost/test/blou/blou" = "blou/blou"
    */
    private function __secondary_chanel($chanel){
      $expl = explode("/", $chanel);
      $toReturn = (count($expl) > 2 ? $expl[2] : $expl[1]);
      for($i = 0 ; $i < count($expl) ; $i++){
        if($i > 2)$toReturn .= "/".$expl[$i];
      }
      return $toReturn;
    }

    /**
      *Description :
    */
    function get($chanel,$callback){
      $primaryChanel = (key_exists("chanel",$GLOBALS) == true && $GLOBALS["chanel"] != null && $GLOBALS["chanel"] != "/" ? $this -> __primary_chanel($GLOBALS["chanel"].$chanel) : $this -> __primary_chanel($chanel));
      $secondaryChanel = (key_exists("chanel",$GLOBALS) == true && $GLOBALS["chanel"] != null && $GLOBALS["chanel"] != "/" ? $this -> __secondary_chanel($GLOBALS["chanel"].$chanel) : $this -> __secondary_chanel($chanel));
      if(key_exists($primaryChanel,$this -> _get) == false)$this -> _get[$primaryChanel] = array();
      $this -> _get[$primaryChanel][$secondaryChanel] = $callback;
    }

    /**
      *Description :
    */
    function post($chanel,$callback){
      $primaryChanel = (key_exists("chanel",$GLOBALS) == true && $GLOBALS["chanel"] != null && $GLOBALS["chanel"] != "/" ? $this -> __primary_chanel($GLOBALS["chanel"].$chanel) : $this -> __primary_chanel($chanel));
      $secondaryChanel = (key_exists("chanel",$GLOBALS) == true && $GLOBALS["chanel"] != null && $GLOBALS["chanel"] != "/" ? $this -> __secondary_chanel($GLOBALS["chanel"].$chanel) : $this -> __secondary_chanel($chanel));
      if(key_exists($primaryChanel,$this -> _post) == false)$this -> _post[$primaryChanel] = array();
      $this -> _post[$primaryChanel][$secondaryChanel] = $callback;
    }

    /**
      *Description :
    */
    function use($c,$option = null){
      $middleware_instance = new $c($option);
      if($middleware_instance -> get_type() == "middleware")array_push($this -> _middleware,array($c => $middleware_instance));
      else echo $c." is not a middleware";
    }

  }

}

?>
