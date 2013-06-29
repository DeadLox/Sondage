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
	<script src="js/action.js"></script>
</head>
<body>
	<div class="container">
		<?php include_once 'include/title.php'; ?>
		<div class="flip-container">
			<div class="form form-sondage">
				<?php include_once 'include/menu.php'; ?>
				<a class="btn btn-primary" href="editForm.php">Créer un nouveau sondage</a>
				<form class="form-search pull-right">
				  <input type="text" class="input-medium search-query formSearch" placeholder="Rechercher...">
				</form>
				<div class="form-search-result"></div>
				<script>
					$(document).ready(function(){
						$('.formSearch').keyup(function(){
							var textVal = $(this).val();
							if (textVal.length >= 3) {
								$.ajax({
									type: 'POST',
									url: 'include/foreachSearchForm.php',
									data: 'textSearch='+textVal,
									success: function(msg){
										$('.form-best').hide();
										$('.form-last').hide();
										$('.form-search-result').html(msg);
									}
								})
							} else {
								$('.form-search-result').html('');
								$('.form-best').show();
								$('.form-last').show();
							}
						});
					});
				</script>
				<?php include_once 'include/foreachLastForm.php'; ?>
				<?php include_once 'include/foreachBestForm.php'; ?>
			</div>
			<?php include_once 'include/loginForm.php'; ?>
			<?php include_once 'include/contactForm.php'; ?>
		</div>
		<?php include_once 'include/footer.php'; ?>
	</div>
</body>
</html>