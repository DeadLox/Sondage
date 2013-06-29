<?php
include_once 'pdo.php';
include_once '../class/Util.class.php';
include_once '../class/Form.class.php';

Form::setPdo($bdd);

if (isset($_POST) && !empty($_POST)) {
	extract($_POST);
}
?>
<div class="portlet">
	<?php
	$listForm = Form::getSearchForm($textSearch);
	?>
	<h2>Recherche (<?php echo sizeof($listForm); ?>):</h2>
	<?php
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
	<?php } else { ?>
		<ul>
			<li>Aucun sondage trouvé.</li>
		</ul>
	<?php } ?>
</div>