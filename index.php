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
			<h1>Accueil Sondage</h1>
			<?php
			$listForm = Form::getLastForm(10);
			if (sizeof($listForm) > 0) { ?>
				<ul>
					<?php
					foreach ($listForm as $key => $form) { ?>
						<li>
							<a href="form.php?id=<?php echo $form->getId(); ?>"><?php echo $form->getName(); ?></a>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
	</div>
</body>
</html>