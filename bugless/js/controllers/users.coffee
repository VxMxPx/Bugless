Bugless.register "Users", ->
	if typeof timezoneArray != 'undefined'
		Select.connect 'select.tz_continent', 'select.tz_country', timezoneArray