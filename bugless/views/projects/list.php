<?php View::Get('_assets/header.php'); ?>
<?php View::Get('_assets/navigation_main'); ?>

<div class="page has_sidebar" id="projects_dashboard">

	<div class="box">
		<span class="c_ico pointer_r"></span>
		<p class="faded"><?php le('NO_PROJECTS_TO_DISPLAY', lh('span.dark')); ?></p>
		<?php if (allow('projects/add')): ?>
			<br />
			<h2><?php le('NO_PROJECTS_IDEA_TO_ADD', lh('a(#) strong')); ?></h2>
		<?php endif; ?>
	</div>

</div> <!-- /#projects_dashboard -->

<?php View::Get('projects/_list_sidebar'); ?>
<?php View::Get('_assets/footer.php'); ?>
