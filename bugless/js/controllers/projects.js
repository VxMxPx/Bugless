Bugless.register('projects', 'controller', {

	newProject: function() {
		$('.no_projects').fadeOut('fast');
	},

	init: function() {
		Bugless.Tags.register('fieldset.tags_container', Bugless_TagsCleanupUrl);
		$('.projects_add').on('click', this.newProject);
	}
});