<div class="portlet form-best">
	<h2>Sondages les plus vus:</h2>
	<?php
	$listForm = Form::getBestForm(10);
	if (sizeof($listForm) > 0) { ?>
		<ol>
			<?php
			foreach ($listForm as $key => $form) { ?>
				<li>
					<a href="form.php?id=<?php echo $form->getId(); ?>"><?php echo $form->getName(); ?></a>
					<span class="label label-info pull-right"><?php echo date('\L\e d:m:Y à H:i:s', $form->getDate()); ?></span>
					<span class="label pull-right"><?php echo $form->getTotal()." Votes"; ?></span>
				</li>
			<?php } ?>
		</ol>
	<?php } ?>
</div>