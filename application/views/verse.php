				<tbody>
<?php	foreach($verse_list as $no => $val) { ?>
					<tr>
						<td><b>[<?=$val->bible_short ?><?=$val->chapter ?>:<?=$val->verse ?>]</b> <?=$val->contents ?></td>
					</tr>
<?php	} ?>
				</tbody>