<!-- Affichage des messages -->
<?php if (isset($msg) && !empty($msg)) { 
	//Util::dump($msg);
	if (isset($msg['error']) && sizeof($msg['error']) > 0) { ?>
		<div class="alert alert-error">
			<button class="close" data-dismiss="alert" type="button">×</button>
			<?php foreach ($msg['error'] as $message) { ?>
				<div><?php echo $message['message']; ?></div>
			<?php } ?>
		</div>
	<?php }
	if (isset($msg['success']) && sizeof($msg['success']) > 0) { ?>
		<div class="alert alert-success">
			<button class="close" data-dismiss="alert" type="button">×</button>
			<?php foreach ($msg['success'] as $message) { ?>
				<div><?php echo $message['message']; ?></div>
			<?php } ?>
		</div>
	<?php }
} ?>
<!-- Fin de l'affichage des messages -->