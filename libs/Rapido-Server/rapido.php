<?php
namespace Rapido{

  include_once("src/Router.php");
  include_once("src/Tools.php");
  include_once("src/Models.php");

  // Router
  class Router extends \Router\Router{}
  class Request extends \Router\Request{}
  class Response extends \Router\Response{}

  // Tools
  class fs extends \Tools\FileSystem{}
  class SQLI extends \Tools\SQLIClient{}
  class Fetch extends \Tools\URLRequest{}
  // Models
  class Error extends \Models\Error{}

}
?>
