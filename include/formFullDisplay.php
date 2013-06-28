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
		    	<div class="bar" data-width="<?php echo $form->getPropositionPercent($proposition['nbReponse']); ?>"><?php echo ($proposition['nbReponse'] > 0)? $proposition['nbReponse'] : "" ; ?></div>
		    </div>
		</li>
	<?php } ?>
	</ul>
	<div>Total: <?php echo $form->getTotal(); ?> votes</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.progress .bar').each(function(){
					var finalWidth = $(this).data('width');
					$(this).animate({"width" : finalWidth+"%"});
				});
			})
		</script>
<?php } ?>