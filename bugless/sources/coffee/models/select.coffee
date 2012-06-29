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