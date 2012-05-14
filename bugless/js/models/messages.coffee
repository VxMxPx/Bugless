Messages = (->
	$list = $ '#messages'

	if $list.length
		$list.fadeIn()
		setTimeout -> 
			$list.fadeOut()
		, 8000

		$list.on 'click',      '.mItem', -> $(this).fadeOut 120
		$list.on 'mouseenter', '.mItem', -> $(this).addClass 'over'
		$list.on 'mouseleave', '.mItem', -> $(this).removeClass 'over'
)()