<div class="box half construction project_add">
	<?php
	echo
		$Form->att('class="styled flat"')->start('#'),
		$Form->att('class="big" placeholder="'.l('TITLE').'"')->textbox('title'),
		$Form->att('class="small resize_none" placeholder="'.l('DESCRIPTION').'" rows="4"')->textarea('description'),
		'<fieldset class="tags_container">
			<legend>'.l('TAGS').'</legend>
			<div class="field no_tags">'.l('NO_TAGS_TO_SELECT_RIGHT_NOW').'</div>
			<div class="tags_list"></div>',
			$Form
				->ins(cHTML::Link('+', '#', 'class="tags button plain"'), true)
				->att('class="tags" placeholder="'.l('ADD_NEW_TAG').'" autocomplete="off"')
				->textbox('tags'),
		'</fieldset>',
		$Form->wrapStart('buttons'),
		cHTML::Link(l('CANCEL'), '#', 'class="cancel button plain"'),
		$Form->wrap(false)->att('class="right"')->button(l('CREATE')),
		$Form->wrapEnd(),
		$Form->end();
	?>
</div>