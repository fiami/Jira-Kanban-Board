<?

/**
 * Class Jira
 * helper class to manage connection to jira and to talk to the API
 */
class Jira {

	/**
	 * @var string path to jira
	 */
	protected $path = "";

	/**
	 * @var string jira username
	 */
	protected $username = "";

	/**
	 * @var string jira password
	 */
	protected $password = "";

	/**
	 * ini new object with the jira path passed
	 * @param $path
	 */
	public function __construct($path) {
		$this->path = $path;
	}

	/**
	 * set the username and password for the current request
	 * this could change later to a token authentification
	 * @param $username
	 * @param $password
	 */
	public function auth($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * get base information about a ticket
	 * function still contains some warnings - should be fixed
	 * @param $ticket
	 * @return array
	 */
	public function baseInformationForIssue($ticket) {
		$name = $ticket->fields->summary;
		$reporter = $ticket->fields->reporter->name;
		$assignee = $ticket->fields->assignee->name;
		$projectName = $ticket->fields->project->name;
		$projectKey = $ticket->fields->project->key;
		$created = strtotime($ticket->fields->created);
		$priority = $ticket->fields->priority->name;
		$status = $ticket->fields->status->name;

		$fixVersionsRaw = $ticket->fields->fixVersions;
		$fixVersions = array();
		if( is_array($fixVersionsRaw) ) {
			foreach( $fixVersionsRaw as $fv ) {
				$fixVersions[]= $fv->name;
			}
		}

		// collect ticket information
		return array(
			"name" => $name,
			"fixVersions" => $fixVersions,
			"status" => $status,
			"reporter" => $reporter,
			"created" => $created,
			"daysSinceCreation" => floor(((time() - $created) / 60 / 60 / 24)),
			"priority" => $priority,
			"assignee" => $assignee,
			"key" => $ticket->key,
			"projectKey" => $projectKey,
			"projectName" => $projectName
		);
	}

	/**
	 * return a list of issues, based on a JQL search string
	 * @param $jql
	 * @return mixed
	 */
	public function getIssuesByJql($jql) {

		// encode jql string, but decode slashes (/),
		// Jira can only handle them decoded
		return $this->query("search?jql=".
			str_replace("%252F", "/",
				rawurlencode($jql)
			)
		);
	}

	/**
	 * get all availabel versions of a project
	 * jira does not provide any limit or order features, yet
	 * @param $projectkey
	 * @return mixed
	 */
	public function getVersionsByProject($projectkey) {
		return $this->query("project/".$projectkey."/versions?");
	}

	/**
	 * query the API with adding username and password
	 * tries to convert json result to objects
	 * @param $query
	 * @return mixed
	 * @throws Exception
	 */
	protected function query($query) {
		$result = file_get_contents($this->path.$query."&os_username=".$this->username."&os_password=".$this->password);
		if( $result === false ) throw new Exception("It wasn't possible to get jira url ".$this->path.$query." with username ".$this->username);
		return json_decode($result);
	}
}