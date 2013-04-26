<?

/**
 * Dispatcher script, which get the controller, action and id from the url
 * this whole script should be moved into a separate classe later
 */
$controller = isset($_GET["c"]) ? $_GET["c"] : "issues";
$action = isset($_GET["a"]) ? $_GET["a"] : "index";
$id = isset($_GET["id"]) ? $_GET["id"] : null;

/**
 * start session
 */
ini_set("session.gc_maxlifetime", 60*60*10);
ini_set("session.cookie_lifetime", "0");
session_start();

/**
 * search for controller controller
 */
if( !file_exists('./controllers/'.$controller.'_controller.php') ) {
	die("Could not find controller");
}
require_once('./controllers/'.$controller.'_controller.php');

/**
 * create object and call action
 */
$controllerClassName = ucfirst($controller)."Controller";
$controller = new $controllerClassName(array(
	"controller" => $controller,
	"action" => $action,
	"id" => $id
));
$controller->proceed();