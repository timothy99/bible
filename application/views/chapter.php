<?php	foreach($chapter_list as $no => $val) { ?>
						<option value="<?=$val->chapter ?>" <?=$val->selected ?>><?=$val->chapter ?>장</option>
<?php	} ?>