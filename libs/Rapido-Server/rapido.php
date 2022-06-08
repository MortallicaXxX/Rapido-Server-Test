<?php
namespace Rapido{

  include_once("src/Middleware.php");
  include_once("src/Router.php");
  include_once("src/Tools.php");
  include_once("src/Models.php");

  // Router
  class Router extends \Router\Router{}
  class Request extends \Router\Request{}
  class Response extends \Router\Response{}

  // // Middleware
  // class Routes extends \Middleware\Routes{}
  // class Sessions extends \Middleware\Sessions{}
  // class BodyParser extends \Middleware\BodyParser{}
  // class Layout extends \Middleware\Layout{}
  // class DotEnv extends \Middleware\DotEnv{}
  // // Datastorage
  // class Datastorage extends \Datastorage\DB{}
  
  // Tools
  class fs extends \Tools\FileSystem{}
  class SQLI extends \Tools\SQLIClient{}
  class Fetch extends \Tools\URLRequest{}
  // Models
  class Error extends \Models\Error{}

}
?>
