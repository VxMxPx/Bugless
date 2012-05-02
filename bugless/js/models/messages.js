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