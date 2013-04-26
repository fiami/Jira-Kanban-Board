<h2>Boards</h2>
<p>Please choose a configuration</p>
<? foreach($boards as $board) { ?>
	<p><a href="<?= $baseUrl ?>/issues/board/<?= urlencode($board) ?>"><?= $board ?></a></p>
<? } ?>
