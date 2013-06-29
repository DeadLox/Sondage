<?php
$showForm = false;
if (isset($form) && !empty($form)) { 
	$showForm = true;
}
if (isset($disableBread) && $disableBread) {
	$showForm = false;
}
?>
<ul class="breadcrumb">
	<li><a href="index.php">Accueil</a><?php if ($showForm) { ?> <span class="divider">/</span><?php } ?></li>
	<?php if ($showForm) { ?>
		<li><a href="form.php?id=<?php echo $form->getId(); ?>"><?php echo $form->getName(); ?></a></li>
	<?php } ?>
	<li class="pull-right">
		<div class="btn-group">
			<button class="btn btn-mini btn-primary" onclick="$('.form-contact').hide();$('.form-login').show();$('.flip-container').toggleClass('rotate');">Se connecter</button>
			<button class="btn btn-mini btn-primary" onclick="$('.form-login').hide();$('.form-contact').show();$('.flip-container').toggleClass('rotate');">Nous contacter</button>
		</div>
	</li>
</ul>