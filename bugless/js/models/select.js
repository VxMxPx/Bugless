Bugless.register('Select', 'model', {

	/**
	 * Will connect two selects together
	 * @param  {string} first  Selector for first select (the one which is static)
	 * @param  {string} second Selector for second select (the one which is dynamic)
	 * @param  {object} values List of all values, need to have key == first.value
	 */
	connect: function(first, second, values) {

		$(first).each(function() {
			var $this = $(this),
				items = '';

			$this.on('change keyup', function() {

				$(second).each(function() {
					var items = '';

					for(var key in values[$this.attr('value')]) {
						if (key) {
							items += '<option value="'+key+'">'+values[$this.attr('value')][key]+'</option>';
						}
					}

					if (items !== '') {
						$(this).html(items);
						$(this).attr('disabled', false);
					}
					else {
						$(this).html('');
						$(this).attr('disabled', 'disabled');
					}
				});
			});

			for(var key in values) {
				items += '<option value="'+key+'">'+key+'</option>';
			}

			$this.html(items).trigger('keyup');
		});
	}

});