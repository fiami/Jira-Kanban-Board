<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?= isset($title) ? $title : "Kanbanboard for Jira" ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le styles -->
		<link href="<?= $baseUrl ?>/public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?= $baseUrl ?>/public/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="./bootstrap/js/html5shiv.js"></script>
		<![endif]-->
		<link href="<?= $baseUrl ?>/public/css/base.css" rel="stylesheet">
	</head>

	<body>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="#"><?= isset($title) ? $title : "Kanbanboard for Jira" ?></a>
					<div class="nav-collapse collapse">
						<? if( !empty($username) ) { ?>
						<ul class="nav">
							<li><a href="/secure/Dashboard.jspa">Jira</a></li>
							<li><a href="<?= $baseUrl ?>/app/logout">Logout</a></li>
						</ul>
						<!-- <form class="navbar-form pull-right">
							<input class="span2" type="text" placeholder="Search tickets" name="search">
							<button type="submit" class="btn">Search</button>
						</form> -->
						<? } ?>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>

		<div class="container">

			<? if(isset($error)) { ?>
			<div class="alert alert-error"><p><strong>Error</strong></p><p><?= $error ?></p></div>
			<? } ?>

			<?= $yield ?>

			<hr>

			<footer>
			<p>&copy; - nope. A free github project.</p>
			</footer>
		</div> <!-- /container -->

		<script src="<?= $baseUrl ?>/public/javascript/jquery-1.9.1.min.js"></script>
		<script src="<?= $baseUrl ?>/public/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
