<?php
include_once 'include/PDO.php';
include_once 'include/autoload.php';

Form::setPdo($bdd);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Acceuil</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
  	<link rel="stylesheet" href="css/style.css" />
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="form">
			<?php include_once 'include/menu.php'; ?>
			<?php include_once 'include/foreachLastForm.php'; ?>
			<?php include_once 'include/foreachBestForm.php'; ?>
		</div>
	</div>
</body>
</html>