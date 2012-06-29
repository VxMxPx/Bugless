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