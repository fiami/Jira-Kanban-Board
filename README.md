Jira Kanban Board
=================
This project is about a small viewer for Jira tickets. They will be shown as a kanban board, based on the configuration
you passed. All functionality is still handled by Jira, the board just shows your tickets in a different order.

One main feature is, that tickets are grouped by their project, so that you will have only one dashboard which shows
tickets in different Jira projects. Your configuration decided which ticket will be shown in which column and which
color the ticket will have.

Flexible configuration
----------------------
Different boards are possible. Each board configuration contains the following setting:

 * All tickets regarding this board will be find by using the jql value
 * The path defines the jira installation to use
 * The columns define all columns for the board, in the same order they are listed here.
 * Each ticket will be passed to each column's callback function and will be shown in the last columns which return true
in the callback function for the ticket. If no column returns a true, the ticket will not be visible in the board. The
first column could return true in every case to act as the "backlog" where all tickets are listed which are not in any
other column.
 * colorCode will be called for every ticket in order to set the right color. Values from 0 till 2 are possible to show
the ticket in green (0), yellow(1) or red(2)

```php
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
```