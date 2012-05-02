(function($, window, undefined) {

	var Bugless = {
		// List of controllers to be autorun and loaded controllers
		List : {
			autorun: ['global'],
			controller: {}
		},

		/**
		 * Register new controller or model
		 * @param  {string} name    controller's / model's name
		 * @param  {string} type    type: controller || model
		 * @param  {object} content
		 */
		register: function(name, type, content) {
			if (type === 'controller') {
				this.List[type][name] = content;
			}
			else if (type === 'model') {
				Bugless[name] = content;
			}
			else {
				console.warn('Invalid type provided: ' + type);
			}
		},

		/**
		 * Will autorun controllers
		 */
		autorun: function() {
			for (var i = 0; i < this.List.autorun.count; i++) {
				this.run(this.autorunList[i]);
			}
		},

		/**
		 * Will run particular controller
		 * @param  {string} controllerName Controller's name
		 */
		run: function(controllerName) {
			this.List.controller[controllerName].init();
		},

		init: function() {
			this.autorun();
			this.Messages.init();
		}
	};

	

Bugless.register('global', 'controller', {
	init: function() {
		// Nothing to see here...
	}
});	

Bugless.register('projects', 'controller', {

	newProject: function() {
		$('.no_projects').fadeOut('fast');
	},

	init: function() {
		$('.projects_add').on('click', this.newProject);
	}
});	

Bugless.register('users', 'controller', {
	init: function() {
		// Connect select on activation
		if (typeof timezoneArray !== 'undefined') {
			Bugless.Select.connect('select.tz_continent', 'select.tz_country', timezoneArray);
		}
	}
});	

Bugless.register('Messages', 'model', {
	init: function() {
		$list = $('#messages');

		if ($list.length) {

			$list.show();

			setTimeout(function() {
				$list.fadeOut('normal', function() {
					$list.remove();
				});
			}, 8000);

			$list.find(".mItem").each(function(index) {
				$message = $(this);
				$message.delay(index*200).fadeIn();

				$message.on('click', function() {
					$(this).fadeOut(120);
				});

				$message.on('mouseenter', function() {
					$(this).addClass('over');
				})
				.on('mouseleave', function() {
					$(this).removeClass('over');
				});
			});
		}
	}
});	

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
	

	Bugless.init();

	// Export to public space!
	window.Bugless = Bugless;

}(jQuery, window));