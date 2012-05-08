Bugless.register('Tags', 'model', {

	onInputEvent: function(values, url) {
		$.post(url, values);
	},

	register: function(container, url) {
		var self       = this,
			$container = $(container),
			$input     = $container.find('input.tags'),
			$addButton = $container.find('a.button.tags');

		$input.on('keypress', function(event) {
			if (event.which == 13) {
				self.onInputEvent($input.val(), url);
				event.preventDefault();
			}
		});

		$addButton.on('click', function() {
			self.onInputEvent($input.val(), url);
		});
	}
});