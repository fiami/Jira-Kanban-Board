<?

/**
 * Class AppController
 * parent controller for all other controllers
 * cares about the rendering, filters, etc.
 */
class AppController {

	/**
	 * @var array vars that will be passed to view
	 */
	private $viewVars = array();

	/**
	 * @var string holds the current username
	 */
	protected $username = "";

	/**
	 * @var string holds the current password
	 */
	protected $password = "";

	/**
	 * @var array current parameters, the request was send with
	 */
	protected $request = array(
		"controller" => "",
		"action" => ""
	);

	/**
	 * @var string base url for this project (should be more intelligent later)
	 */
	public $baseUrl = "/Jira-Kanban-Board";

	/**
	 * contructor which gets the main request parameters passed
	 * @param $request
	 */
	public function __construct($request) {
		$this->request = $request;
	}

	/**
	 * will be called, before the actual action will be called
	 * does some security checks
	 */
	protected function beforeFilter() {

		// login data from session
		if( isset($_SESSION["security"]) ) {
			$this->username = isset($_SESSION["security"]["username"]) ? $_SESSION["security"]["username"] : "";
			$this->password = isset($_SESSION["security"]["password"]) ? $_SESSION["security"]["password"] : "";
		}

		// only allow login action, when user is not logged in
		if( (empty($this->username) || empty($this->password)) &&
		    ($this->request["controller"] != "app" || $this->request["action"] != "login") ) {
			$this->redirect('/app/login');
		}

		// pass base url and username to view
		$this->passVar("baseUrl", $this->baseUrl);
		$this->passVar("username", $this->username);
	}

	/**
	 * pass vars from your controller to the view
	 * @param $name
	 * @param $val
	 */
	protected function passVar($name, $val) {
		$this->viewVars[$name] = $val;
	}

	/**
	 * redirect the current page to a different path
	 * @param $path
	 */
	protected function redirect($path) {
		Header("Location: ".$this->baseUrl.$path);
		die();
	}

	/**
	 * main action in order to process the current request
	 * calls the different callbacks (beforeFilter, afterFilter) and in between the action itself
	 * as a result this function calls the right view and includes everything in the main layout
	 */
	public function proceed() {

		try {
			$action = $this->request["action"];

			// execute action
			$this->beforeFilter();
			$this->$action();
			$this->afterFilter();

			// render view
			extract($this->viewVars);
			ob_start();
			$mviewDir = lcfirst(str_replace('Controller', '', get_class($this)));
			require(__DIR__."/../views/".$mviewDir."/".$action.".php");
			$yield = ob_get_contents();
			ob_end_clean();

		} catch (Exception $e) {
			$this->passVar("error", $e->getMessage());
			extract($this->viewVars);
		}

		// render layout
		require(__DIR__."/../views/layouts/default.php");
	}

	/**
	 * will be called, after the actual action will be called
	 */
	protected function afterFilter() {
	}

	/**
	 * action to show the login form
	 */
	public function login() {
		if( isset($_POST["username"]) && $_POST["password"]) {
			$_SESSION["security"] = array(
				"username" => $_POST["username"],
				"password" => $_POST["password"]
			);
			$this->redirect("/");
		}
	}

	/**
	 * action to log out the user
	 */
	public function logout() {
		unset($_SESSION["security"]);
		$this->redirect("/");
	}
}