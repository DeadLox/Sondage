<div class="portlet">
	<h2>Sondages les plus vus:</h2>
	<?php
	$listForm = Form::getBestForm(10);
	if (sizeof($listForm) > 0) { ?>
		<ol>
			<?php
			foreach ($listForm as $key => $form) { ?>
				<li>
					<a href="form.php?id=<?php echo $form->getId(); ?>"><?php echo $form->getName(); ?></a>
				</li>
			<?php } ?>
		</ol>
	<?php } ?>
</div>