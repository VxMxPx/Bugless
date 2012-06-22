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
	# @param	string	first		Selector for first select (the one which is static)
	# @param	string	second		Selector for second select (the one which is dynamic)
	# @param	object	values		List of all values, need to have key == first.value
	# @param	array	defaults	default selected value for first and second select
	connect = (first, second, values, defaults)->
		$(first).each ->
			$this = $(this)
			items = ''
			defaults = defaults || [false, false]
			selected = ''

			Log.add(defaults);

			$this.on 'change keyup', ->
				$(second).each ->
					items = ''
					for key, val of values[$this.attr 'value'] when key
						selected = if defaults[1] == key && defaults[0] == $this.attr 'value' then ' selected="selected"' else ''
						items += '<option' + selected + ' value"' + key + '">' + values[$this.attr 'value'][key] + '</option>' 

					if items != ''
						$(this).html items
						$(this).attr 'disabled', false
					else
						$(this).html ''
						$(this).attr 'disabled', 'disabled'

			for key, val of values
				selected = if defaults[0] == key then ' selected="selected"' else ''
				items += '<option' + selected + ' values="' + key + '">' + key + '</option>' 

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

Bugless.register "Users", ->
	if typeof timezoneArray != 'undefined'
		Select.connect 'select.tz_continent', 'select.tz_country', timezoneArray, defaultsItems

	# Password change checkbox
	password_change = $('#aff__change_password_yes')
	password_wrap   = $('.password_wrap')
	if password_change.length > 0
		if not password_change.is ':checked'
			password_wrap.hide()
		else
			password_wrap.show()
		password_change.on 'click', ->
			if password_change.is ':checked'
				password_wrap.slideDown 'fast'
			else
				password_wrap.slideUp 'fast'
# Export Bugless
window.Bugless = Bugless