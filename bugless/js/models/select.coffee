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