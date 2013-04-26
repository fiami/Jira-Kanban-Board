<?php
/**
 * Sample configuration to show how a board can be configured.
 */
$sources = array(

	// name if the current set
	"Board-Name" => array(

		// Query string to get tickets via search
		"jql" => "status != 'Closed'",

		// path to jira rest API
		"path" => "https://jira.yourdomain.com:1234/rest/api/2/",

		// columns to display
		"columns" => array(

			array(
				"name" => "Columnname",
				"callback" => function( $ticket, $project ) {
					$showTicketInThisCol = true;
					return $showTicketInThisCol;
				}
			),
		),

		// define which color to use for a passed ticket (return value 0=green, 1=yellow, 2=red)
		"colorCode" => function( $ticket ) {
			$green = 0;
			$yellow = 1;
			$red = 2;
			return $green;
		}
	)
);

