				<tbody>
<?php	foreach($verse_list as $no => $val) { ?>
					<tr>
						<th style="width:25%"><?=$val->bible_short ?><?=$val->chapter ?>:<?=$val->verse ?></th>
						<td><?=$val->contents ?></td>
					</tr>
<?php	} ?>
				</tbody>