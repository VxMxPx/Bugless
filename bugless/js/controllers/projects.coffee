Bugless.register "Projects", ->
	newProject = ->
		$('.no_projects').fadeOut 'fast'

	tags = Tags('fieldset.tags_container', Bugless_TagsCleanupUrl)
	$('.projects_add').on 'click', newProject