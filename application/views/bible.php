<?php	foreach($bible_list as $no => $val) { ?>
						<option value="<?=$val->bible_short ?>" <?php if($val->bible_short == $data["bible_short"]) echo "selected"; ?>><?=$val->bible_name ?>(<?=$val->bible_short ?>)</option>
<?php	} ?>