<!-- Affichage des messages -->
<?php if (isset($msg) && !empty($msg)) { ?>
	<div class="alert <?php echo 'alert-'.$msg['type']; ?>">
		<button class="close" data-dismiss="alert" type="button">×</button>
		<?php echo $msg['message']; ?>
	</div>
<?php } ?>
<!-- Fin de l'affichage des messages -->