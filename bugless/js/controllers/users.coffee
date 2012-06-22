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