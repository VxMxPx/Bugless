Bugless.register('users', 'controller', {
	init: function() {
		// Connect select on activation
		if (typeof timezoneArray !== 'undefined') {
			Bugless.Select.connect('select.tz_continent', 'select.tz_country', timezoneArray);
		}
	}
});