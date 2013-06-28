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

			<form method="POST" action="">
				<label>Titre du sondage:</label>
				<input class="input-xxlarge" type="text" name="titre" />
				<label>Question:</label>
				<input class="input-xxlarge" type="text" name="question" />
				<div class="proposition_template" style="display:none;">
					<label>Proposition:</label>
					<div class="input-append">
					  <input class="input-xxlarge" type="text" name="proposition" />
					  <button class="btn btn-danger" type="button" onclick="removeProp($(this))">-</button>
					  <button class="btn" type="button" onclick="addProp()">+</button>
					</div>
				</div>
				<div class="listProposition">
				</div>
				<input type="hidden" name="id_form" value="" />
				<button class="btn btn-primary" type="submit">Créer le sondage</button>
			</form>
			<script>
				var nbProp = 0;
				$(document).ready(function(){
					addProp();
				})
				function addProp(){
					var temp = $('.proposition_template').html();
					$('.listProposition .input-append').addClass('noButton');
					$('.listProposition').append(temp);
					nbProp++;
					if (nbProp == 1) {
						$('.listProposition .input-append').addClass('oneButton').find('.btn-danger').hide();
					}
				}
				function removeProp(prop){
					if (nbProp > 1) {
						var parent = prop.parent();
						parent.prev().remove();
						parent.remove();
						$('.listProposition .input-append:last').removeClass('noButton');
						nbProp--;
					}	
				}
			</script>
		</div>
	</div>
</body>
</html>