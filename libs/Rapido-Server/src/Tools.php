<?php

namespace Tools{
  /**
    *Name : Router
    *Type : Class
    *Description :
    *Use-case :
    *Sample :
  */
  class FileSystem{

    /** @source https://www.w3schools.com/PHP/php_ref_filesystem.asp*/

    /**
      *Description :
    */
    function extension($path){
      $path_info = pathinfo($path);
      return $path_info['extension'];
    }

    /**
      *Description :
    */
    function filename($path){
      $path_info = pathinfo($path);
      return $path_info['filename'];
    }

    /**
      *Description :
    */
    function write_file($full_path,$data){
      $file = fopen($full_path, "w") or die("Unable to open file!");
      fwrite($file, $data);
      fclose($file);
    }

    /**
      *Description :
    */
    function write_in_file($full_path,$data){

    }

    /**
      *Description :
    */
    function read_file($full_path){
      return file_get_contents($full_path);
    }

    /**
      *Description :
    */
    function print_file($full_path){
      echo readfile($full_path);
    }

    /**
      *Description :
    */
    function add_folder($full_path){
      mkdir($full_path,0777);
    }

    /**
      *Description :
    */
    function add_file($full_path){
      $file = fopen($full_path, "w") or die("Unable to open file!");
      fclose($file);
    }

  }

  /**
    *Name : Router
    *Type : Class
    *Description :
    *Use-case :
    *Sample :
  */
  class SQLIClient{

    private $_host;
    private $_port;
    private $_user;
    private $_password;
    private $_dbname;

    private $mysqli;

    function __construct($host,$port,$user,$password,$db){
      $this -> _host = $host;
      $this -> _port = $port;
      $this -> _user = $user;
      $this -> _password = $password;
      $this -> _dbname = $db;
      $this -> Connect();
    }

    /**
      *Description :
    */
    function Connect(){
      $this -> mysqli = new \mysqli( $this -> _host , $this->_user , $this->_password , $this->_dbname , $this -> _port );
      \mysqli_set_charset($this -> mysqli,"utf8");
      if($this -> mysqli -> connect_errno){
        $this -> onError($this -> mysqli -> connect_error);
      }
    }

    function Set_Option($key,$value){
      if($key == "charset")mysqli_set_charset($this -> mysqli,"utf8");
    }

    /**
      *Description :
    */
    function Query($query,$callback=null,$option=null){
      $resultQuery = $this -> mysqli -> query($query); // exécution query
      $error = (!$resultQuery ? $this -> mysqli -> error : null); // gestion erreur
      if($callback)$callback($error,$this -> Normalize($resultQuery) , $this , $option); // exécution du callBack
      else if($error)return $error;
      else return $this -> Normalize($resultQuery);
    }

    /**
      *Description :
    */
    function Normalize($result){
      $toReturn = [];
      for ($i = 1; $i <= $result -> num_rows; $i++) {
        $row = $result->fetch_assoc(); //
        array_push($toReturn, $row);
      }
      return $toReturn;
    }

    /**
      *Description :
    */
    function Close(){
      $this -> mysqli -> close();
    }

    /**
      *Description :
    */
    function onError($message){
      echo $message;
    }

  }

  class URLRequest{

    private $_url;
    private $options;

    function __construct($url,$options,$callback=null,$arg=null){
      $this -> _url = $url;
      $this -> options = $options;
    }

    function get(){

      $curl = curl_init($this -> _url);
      curl_setopt($curl, CURLOPT_URL, $this -> _url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

      $resp = curl_exec($curl);
      curl_close($curl);
      return $resp;

    }

    function post($data){

      $curl = curl_init($this -> _url);
      curl_setopt($curl, CURLOPT_URL, $this -> _url);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      $headers = array(
         "Content-Type: application/json",
      );
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

      $resp = curl_exec($curl);
      curl_close($curl);
      return $resp;

    }

    function exec($callback=null){
      if($callback){
        if($this -> options["method"] == "GET")$callback($this -> get(),$arg);
        if($this -> options["method"] == "POST")$callback($this -> post(
              (array_key_exists("body",$this -> options) ? $this -> options["body"] : array())
            ),$arg);
      }
      else{
        if($this -> options["method"] == "GET")return $this -> get();
        if($this -> options["method"] == "POST")return $this -> post((array_key_exists("body",$this -> options) ? $this -> options["body"] : array()));
      }
    }

  }

}

?>
