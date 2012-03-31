<?php View::Get('_assets/header.php'); ?>
<?php View::Get('_assets/navigation_main'); ?>

<div class="page has_sidebar" id="projects_dashboard">

	<div class="box construction">
		<span class="c_ico pointer_r"></span>
		<p class="faded">Oh no, there are <span class="dark">no projects</span> to display at the moment...</p>
		<br />
		<h2>But hej! Here's an *idea*, why don't you <a href="#"><strong>add a new project</strong></a>?</h2>
	</div>

</div> <!-- /#projects_dashboard -->

<?php View::Get('projects/_list_sidebar'); ?>
<?php View::Get('_assets/footer.php'); ?>
