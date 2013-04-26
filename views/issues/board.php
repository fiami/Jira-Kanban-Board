<? foreach($projects as $project => $tickets) { ?>
<div class="row">
	<div class="span12">
		<h3><?= $project ?></h3>
	</div>
</div>

<ul class="thumbnails">
	<? foreach($cols as $col) {?>
	<li class="span<?= floor(12/count($cols)) ?>">
		<h6><?= $col["name"] ?></h6>
		<? if(!isset($projects[$project][$col["name"]]) || count($projects[$project][$col["name"]]) == 0) { ?>
			<div class="thumbnail issue issue_spaceholder">
				&nbsp;
			</div>
		<? } else { ?>
			<? foreach($projects[$project][$col["name"]] as $issue) {
				$urgency = $colorCode($issue);
				$css = "issue_green";
				if( $urgency == 1 ) $css = "issue_yellow";
				if( $urgency == 2 ) $css = "issue_red";
			?>

			<div class="thumbnail issue <?= $css ?>">
				<div>
					<?= "<div class='title'><a href='/browse/".$issue["key"]."'>".$issue["name"]."</a></div>"; ?>
				</div>

				<div class="details"><?= $issue["priority"]; ?></div>
				<div class="details"><?= date("d.m.Y", $issue["created"])." (".$issue["daysSinceCreation"]." days ago)"; ?></div>
				<div class="details"><?= "".$issue["reporter"]." > ".$issue["assignee"]; ?></div>
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
