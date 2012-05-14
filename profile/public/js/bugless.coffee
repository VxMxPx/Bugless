Bugless = (->

	controllers = {}

	run = (controllerName)->
		controllers[controllerName]()

	register = (controllerName, code)->
		controllers[controllerName] = code

	# Public methods!
	return {
		run: run
		register: register
	}
)()



Log = (->
	# Possbile types: log | warn | error | info
	add = (message, type='log')->
		if typeof console == 'object'
			console[type] message

	# Public methods
	return {
		add: add
	}
)()

Messages = (->
	$list = $ '#messages'

	if $list.length
		$list.fadeIn()
		setTimeout -> 
			$list.fadeOut()
		, 8000

		$list.on 'click',      '.mItem', -> $(this).fadeOut 120
		$list.on 'mouseenter', '.mItem', -> $(this).addClass 'over'
		$list.on 'mouseleave', '.mItem', -> $(this).removeClass 'over'
)()

Select = (->
	# Will connect two select together
	# @param	string	first	Selector for first select (the one which is static)
	# @param	string	second	Selector for second select (the one which is dynamic)
	# @param	object	values	List of all values, need to have key == first.value
	connect = (first, second, values)->
		$(first).each ->
			$this = $(this)
			items = ''

			$this.on 'change keyup', ->
				$(second).each ->
					items = ''
					for key, val of values[$this.attr 'value'] when key
						items += '<option value"' + key + '">' + values[$this.attr 'value'][key] + '</option>' 

					if items != ''
						$(this).html items
						$(this).attr 'disabled', false
					else
						$(this).html ''
						$(this).attr 'disabled', 'disabled'

			items += '<option values="' + key + '">' + key + '</option>' for key, val of values

			$this.html(items).trigger 'keyup'

	return {
		connect: connect
	}
)()

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

Bugless.register "Projects", ->
	newProject = ->
		$('.no_projects').fadeOut 'fast'

	tags = Tags('fieldset.tags_container', Bugless_TagsCleanupUrl)
	$('.projects_add').on 'click', newProject

Bugless.register "Users", ->
	if typeof timezoneArray != 'undefined'
		Select.connect 'select.tz_continent', 'select.tz_country', timezoneArray
# Export Bugless
window.Bugless = Bugless