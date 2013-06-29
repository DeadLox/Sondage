<?php
include_once 'include/PDO.php';
include_once 'include/autoload.php';

Form::setPdo($bdd);

$disableBread = true;

if (isset($_POST) && !empty($_POST)) {
	extract($_POST);
	$form = new Form($id_form, $titre, $question, 0);
	$form->createProp($propositions);
	$msg = $form->performCreate();
}
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
		<?php include_once 'include/title.php'; ?>
		<div class="flip-container">
			<div class="form form-sondage">
				<?php include_once 'include/menu.php'; ?>

				<?php include 'include/msg.php'; ?>
				<?php if (!isset($msg['success'])) { ?>
					<!-- TABS -->
					<ul class="nav nav-tabs" id="tab-form">
					  <li class="active"><a href="#conf" data-toggle="tab">Configuration</a></li>
					  <li><a href="#data" data-toggle="tab">Sondage</a></li>
					</ul>

					<form method="POST" action="">
						<div class="tab-content">
							<div class="tab-pane active" id="conf">
								<label>Type du sondage:</label>
								<div class="btn-group" data-toggle="buttons-radio">
								  <button type="button" class="btn btn-primary active" onclick="$('.pwd-form').slideUp()">Publique</button>
								  <button type="button" class="btn btn-primary" onclick="$('.pwd-form').slideDown()">Privé</button>
								</div>
								<div class="pwd-form" style="display:none;">
									<label>Mot de passe:</label>
									<input class="input-xxlarge" type="password" name="pwd"/>
								</div>
								<label>Affichage:</label>
								<div class="btn-group" data-toggle="buttons-radio">
								  <button type="button" class="btn btn-primary active">Bouton radio</button>
								  <button type="button" class="btn btn-primary">Checkbox</button>
								  <button type="button" class="btn btn-primary">Liste déroulante</button>
								</div>
								<div>
									<button class="btn btn-primary pull-right" onclick="$('#tab-form a:last').tab('show');return false;">Suivant &gt;</button>
								</div>
							</div>
							<!-- tab sondage -->
							<div class="tab-pane" id="data">
								<label>Titre du sondage:</label>
								<input class="input-xxlarge" type="text" name="titre" />
								<label>Question:</label>
								<input class="input-xxlarge" type="text" name="question" />
								<div class="listProposition">
								</div>
								<input type="hidden" name="id_form" value="" />
								<button class="btn btn-primary" onclick="$('#tab-form a:first').tab('show');return false;">&lt; Précédent</button>
								<button class="btn btn-primary pull-right" type="submit">Créer le sondage</button>
							</div>
						</div>
					</form>

					<div class="proposition_template" style="display:none;">
						<label>Proposition n°<span class="id_prop">0</span>:</label>
						<div class="input-append">
						  <input class="input-xxlarge" type="text" name="propositions[]" />
						  <button class="btn btn-danger" type="button" onclick="removeProp($(this))">-</button>
						  <button class="btn" type="button" onclick="addProp()">+</button>
						</div>
					</div>
					<script>
						var nbProp = 0;
						$(document).ready(function(){
							addProp();
						})
						function addProp(){
							nbProp++;
							var temp = $('.proposition_template');
							temp.find('.id_prop').html(nbProp);
							$('.listProposition .input-append').addClass('noButton');
							$('.listProposition').append(temp.html());
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
				<?php } ?>
			</div>
		</div>
	</div>
</body>
</html>