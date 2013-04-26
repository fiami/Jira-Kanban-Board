<?

/**
 * require the app controller class
 */
require(__DIR__."/app_controller.php");

/**
 * Class IssuesController
 * handles all issue realted stuff, like showing the boards, etc.
 */
class IssuesController extends AppController {

	/**
	 * redirect index action to board overview
	 */
	public function index() {
		$this->redirect("/issues/boards");
	}

	/**
	 * load the boards from the config and show it to the user
	 */
	public function boards() {
		$sources = $this->getBoardsFromConfig();
		$this->passVar("boards", array_keys($sources));
	}

	/**
	 * load a certain board, passed by the id in url
	 */
	public function board() {

		// get configuration
		$sources = $this->getBoardsFromConfig();
		$board = $this->request["id"];
		$config = $sources[$board];

		// connect to jira
		require(dirname(__FILE__)."/../library/Jira.php");
		$jira = new Jira($config["path"]);
		$jira->auth($this->username, $this->password);

		// collect issues
		$rawIssues = $jira->getIssuesByJql($config["jql"]);
		$projects = array();
		$nextReleaseVersion = array();
		foreach( $rawIssues->issues as $ticket) {

			// collect issue information
			$ticketDetails = $jira->baseInformationForIssue($ticket);

			$projectKey = $ticketDetails["projectKey"];
			$projectName = $ticketDetails["projectName"];
			if( !isset($nextReleaseVersion[$projectKey]) ) {
				$versions = $jira->getVersionsByProject($projectKey);
				foreach( $versions as $version ) {
					if( $version->released != 1 ) {
						if(!isset($nextReleaseVersion[$projectKey]) || $nextReleaseVersion[$projectKey]["date"] > $version->releaseDate ) {
							$nextReleaseVersion[$projectKey] = array(
								"date" => (isset($version->releaseDate) ? $version->releaseDate : ""),
								"name" => $version->name
							);
						}
					}
				}
			}

			// collect version information
			$project = array(
				"key" => $projectKey,
				"currentVersion" => (isset($nextReleaseVersion[$projectKey]) ? $nextReleaseVersion[$projectKey] : array())
			);

			// set right column
			$col = null;
			foreach( $config["columns"] as $column ) {
				// echo $column["name"];
				if( $column["callback"]($ticketDetails, $project) === true ) {
					$col = $column["name"];
				}
			}

			// pass tickets of we were able to find a column
			if( $col !== null ) {
				if( !isset($projects[$projectName]) ) $projects[$projectName] = array();
				if( !isset($projects[$projectName][$col]) ) $projects[$projectName][$col] = array();
				$projects[$projectName][$col][]= $ticketDetails;
			}
		}

		$this->passVar("projects", $projects);
		$this->passVar("cols", $config["columns"]);
		$this->passVar("colorCode", $config["colorCode"]);
		$this->passVar("title", "Board: ".$board);
	}

	/**
	 * helper function to read the config - should be smarter than require the current file
	 * @return array
	 */
	protected function getBoardsFromConfig() {
		$sources = array();
		require_once(dirname(__FILE__)."/../config.php");
		return $sources;
	}
}