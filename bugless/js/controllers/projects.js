Bugless.register('projects', 'controller', {

	newProject: function() {
		$('.no_projects').fadeOut('fast');
	},

	init: function() {
		$('.projects_add').on('click', this.newProject);
	}
});