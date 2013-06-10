/*
You can retrieve the editable fields and the operations they support using the "editmeta" resource. For example:
http://MYJIRA.INSTALL.COM:8080/rest/api/2/issue/ISSUE-KEY/editmeta
*/

window.kanbanSources = {

	// board name
	"IT Services" : {

		// define callbacks for user interaction
		"callbacks" : {

			// callback when user drags issue from col 0 to col 1 etc. Insert fields according to Jira Rest Api and/or define your custom callback function
			"switchCol1To2" : {
				transition :
				{
					id:"1"
				},
				update:
				{
					assignee:[ {set: {name:"Peter"}} ],
					comment:[ {add: {body:"Your comment"}} ]
				},
				custom : "yourcustomfunction"
			}
		},

		//add custom callback functions. call by adding custom:"yourfunction" to "switchColXToX"
		"custom" : {
			"yourcustomfunction" : function(event, ui, source, updateFields){
				return {
					transition : {
						id:"2"
					},
					update:	{
						assignee:[ {set: {name:"Overwritten assignee"}} ],
							comment:[ {add: {body:"Overwritten comment"}} ]
					}
				}
			}
		}
	}

};