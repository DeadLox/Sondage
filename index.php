<?php
include_once 'include/PDO.php';
include_once 'class/Form.class.php';

Form::setPdo($bdd);

// Récupère l'ID du formulaire passé en paramètre et instanci un nouveau Formulaire
if (isset($_GET) && !empty($_GET)) {
	extract($_GET);
	$form = Form::getForm($id);
}
if (isset($_POST) && !empty($_POST)) {
	extract($_POST);
	if (isset($proposition_id)) {
		$msg = $form->addEntry($proposition_id);
		$form = Form::getForm($id);
	// Si le Formulaire a été soumis sans réponse de cochée
	} else {
		$msg[] = array("type" => "error", "message" => "Veuillez sélectionner une réponse.");
	}
}
//var_dump($form);
//echo $_SERVER['REMOTE_ADDR'];
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="css/bootstrap.min.css" />
  	<link rel="stylesheet" href="css/style.css" />
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="form">
			<?php include 'include/msg.php'; ?>
			<h1><?php echo $form->getName(); ?></h1>
			<h2><?php echo $form->getQuestion(); ?></h2>
			<!-- Formulaire -->
			<?php if (!$form->checkAlreadySubmit()) { ?>
				<form method="POST" action="">
					<ul>
					<?php foreach ($form->getListeProposition() as $key => $proposition) { ?>
						<li>
							<label for="<?php echo $proposition['id']; ?>">
								<input type="radio" id="<?php echo $proposition['id']; ?>" name="proposition_id" value="<?php echo $proposition['id']; ?>" />
								<?php echo $proposition['titre']; ?>
							</label>
						</li>
					<?php } ?>
					</ul>
					<input type="hidden" name="id_form" value="<?php echo $form->getId(); ?>">
					<input class="btn" type="submit" value="Répondre" />
				</form>
			<?php } else { ?>
				<ul>
				<?php foreach ($form->getListeProposition() as $key => $proposition) { ?>
					<li>
						<label><?php echo $proposition['titre']; ?></label>
					    <div class="progress progress-striped active">
					    	<div class="bar" style="width: <?php echo $form->getPropositionPercent($proposition['nbReponse']); ?>%;"><?php echo ($proposition['nbReponse'] > 0)? $proposition['nbReponse'] : "" ; ?></div>
					    </div>
					</li>
				<?php } ?>
				</ul>
				<div>Total: <?php echo $form->getTotal(); ?> votes</div>
				<!-- Options
			    <div class="btn-group">
	                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Plus <span class="caret"></span></button>
	                <ul class="dropdown-menu">
	                  <li><a href="#">Changer mon vote</a></li>
	                  <li><a href="#">Supprimer mon vote</a></li>
	                </ul>
              	</div>
          		-->
			<?php } ?>
		</div>
	</div>
</body>
</html>