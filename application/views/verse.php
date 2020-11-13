				<tbody>
<?php	foreach($verse_list as $no => $val) { ?>
<?php		if($val->title != null) { // 제목이 있는 성경구절의 경우 제목을 보여줌 ?>
					<tr>
						<td>
							<b><?=$val->title ?></b>
						</td>
					</tr>
<?php		} ?>
					<tr>
						<td>
							<b>[<?=$val->bible_short ?><?=$val->chapter ?>:<?=$val->verse ?>] </b>
							<?=$val->contents ?>
						</td>
					</tr>
<?php	} ?>
				</tbody>