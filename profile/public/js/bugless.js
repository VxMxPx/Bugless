(function($e, window, undefined) {

	var Messages = {

		/**
		 * Will initialize messages
		 * --
		 * @returns	void
		 */
		Init : function() {

			var $list = $('#messages');

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
		//--

	};
	//--

	/**
	 * Some global basic functions, valid all arround
	 */
	var System = {
		Init : function() {
			// Connect select => array
			$('select.hook').each(function() {
				var $this   = $(this),
					classes = $this.attr('class').split(' '),
					id_class = '',
					list     = '';

				$this.on('change keyup', function() {

					$('select.list_'+id_class+'.hook_second').each(function() {
						var items = '';

						for(key in list[$this.attr('value')]) {
							items += '<option value="'+key+'">'+list[$this.attr('value')][key]+'</option>';
						}

						$(this).html(items);
					});
				});

				for(var i = 0; i<classes.length; i++) {
					if (classes[i].substr(0,5) == 'list_') {
						var items = '';

						id_class = classes[i].substr(5);
						list     = window[id_class];

						for(key in list) {
							items += '<option value="'+key+'">'+key+'</option>';
						}

						$this.html(items).trigger('keyup');
					}
				}
			});
		}
	};
	//--

	// Init All
	Messages.Init();
	System.Init();

}(jQuery, window));
