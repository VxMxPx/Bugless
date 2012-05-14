Tags = (container, url)->

	$container = $(container)
	$input = $container.find 'input.tags'
	$addButton = $container.find 'a.button.tags'

	tagsRequest = (values, url)->
		$.ajax({
			type: 'POST'
			url: url
			data: {'tags': values}
			dataType: 'json'
		})
		.success (data)->
			Log.add data, 'log'

	$input.on 'keypress', (event)->
		if event.which == 13
			tagsRequest $input.val(), url
			event.preventDefault()

	$addButton.on 'click', ->
		tagsRequest $input.val(), url