<?php
include_once 'include/PDO.php';
include_once 'include/autoload.php';

Form::setPdo($bdd);

// Récupère l'ID du formulaire passé en paramètre et instanci un nouveau Formulaire
if (isset($_GET) && !empty($_GET)) {
	extract($_GET);
	$form = Form::getForm($id);
}
// Si le Formualire a été soumis
if (isset($_POST) && !empty($_POST)) {
	extract($_POST);
	if (isset($proposition_id)) {
		$msg = $form->addEntry($proposition_id);
		$form = Form::getForm($id);
	// Si le Formulaire a été soumis sans réponse de cochée
	} else {
		$msg = array("type" => "error", "message" => "Veuillez sélectionner une réponse.");
	}
}
// Si le Formualire est null, on affiche un message d'erreur
if (!isset($form) || is_null($form)) {
	$msg = array("type" => "warning", "message" => "Ce sondage n'existe pas.");
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo (isset($form) && !empty($form))? $form->getName() : "Sondage"; ?></title>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
  	<link rel="stylesheet" href="css/style.css" />
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="form">
			<div class="btn-group navbar-form">
			  <a href="index.php" class="btn btn-mini btn-primary">Retour</a>
			</div>
			<?php include 'include/msg.php'; ?>
			<?php if (isset($form) && !empty($form)) {
				include_once 'include/formFullDisplay.php';
			} else { ?>
				<a class="btn btn-primary" href="index.php">Retour à l'accueil</a>
			<?php } ?>
		</div>
	</div>
</body>
</html>