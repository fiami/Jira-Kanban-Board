<? foreach($projects as $project => $tickets) { ?>
<div class="row">
	<div class="span12">
		<h3><?= $project ?></h3>
	</div>
</div>

<ul class="thumbnails">
	<? foreach($cols as $col) {?>
	<li class="span<?= floor(12/count($cols)) ?> droppable">
		<h6><?= $col["name"] ?></h6>
		<? if(!isset($projects[$project][$col["name"]]) || count($projects[$project][$col["name"]]) == 0) { ?>
			<div class="thumbnail issue issue_spaceholder">
				&nbsp;
			</div>
		<? } else { ?>
			<? foreach($projects[$project][$col["name"]] as $issue) {
				$key = $issue["key"];
				$urgency = $colorCode($issue);
				$css = "issue_green";
				if( $urgency == 1 ) $css = "issue_yellow";
				if( $urgency == 2 ) $css = "issue_red";
			?>

			<div class="thumbnail issue <?= $css ?> draggable" data-key="<?= $key ?>" data-assignee="<?= $issue["assignee"]; ?>">
				<div>
					<?= "<div class='title'><a target='_blank' href='".implode('/', explode('/', $path, -4))."/browse/".$key."'>".$issue["name"]."<small> (".$key.")</small></a></div>"; ?>
				</div>

				<div class="details"><?= $issue["priority"]; ?></div>
				<div class="details"><?= date("d.m.Y", $issue["created"])." (".$issue["daysSinceCreation"]." days ago)"; ?></div>
				<? if( $issue["dueDate"] ) { ?>
			    		<div class="details"><?= "due ".date("d.m.Y", $issue["dueDate"])." (in/bef ".$issue["daysUntilDueDate"]." days)"; ?></div>
				<? } ?>
				<div class="details"><?= "".$issue["reporter"]." > <span id='currentAssignee'>".$issue["assignee"]."</span>"; ?></div>
				<? if( count($issue["fixVersions"]) > 0 ) { ?>
					<div class="details"><?= implode(", ", $issue["fixVersions"]); ?></div>
				<? } ?>
			</div>
			<? } ?>
		<? } ?>
	</li>
	<? } ?>
</ul>
<? } ?>