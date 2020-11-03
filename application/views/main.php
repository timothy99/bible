<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!doctype html>
<html lang="ko">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>bible search</title>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<style>
			.row { margin-top : 10px;}
		</style>
	</head>
	<body>
		<form id="frm" name="frm">
			<input type="hidden" id="bible_input" name="bible_input" value="<?=$data["bible_short"] ?>">
			<div class="container">
				<div class="row">
					<div class="col-3">
						<button type="button" class="btn btn-info btn-block" onclick="search_bible('구약')">구약</button>
					</div>
					<div class="col-3">
						<button type="button" class="btn btn-warning btn-block" onclick="search_bible('신약')">신약</button>
					</div>
					<div class="col-3">
						<button type="button" class="btn btn-danger btn-block" onclick="move_chapter('prev')">이전</button>
					</div>
					<div class="col-3">
						<button type="button" class="btn btn-success btn-block" onclick="move_chapter('next')">다음</button>
					</div>
				</div>
				<div class="row">
					<div class="col-7">
						<select class="form-control" id="bible" name="bible" onchange="search_chapter(this.value)">
<?php	foreach($bible_list as $no => $val) { ?>
							<option value="<?=$val->bible_short ?>" <?php if($val->bible_short == $data["bible_short"]) echo "selected"; ?>><?=$val->bible_name ?>(<?=$val->bible_short ?>)</option>
<?php	} ?>
						</select>
					</div>
					<div class="col-5">
						<select class="form-control" id="chapter" name="chapter" onchange="search_verse(this.value)">
<?php	foreach($chapter_list as $no => $val) { ?>
							<option value="<?=$val ?>"><?=$val ?>장</option>
<?php	} ?>
						</select>
					</div>
				</div>
				<div class="row"></row>
				<table class="table table-condensed table-hover" id="verse">
					<tbody>
<?php	foreach($verse_list as $no => $val) { ?>
						<tr>
							<td><b>[<?=$val->bible_short ?><?=$val->chapter ?>:<?=$val->verse ?>]</b> <?=$val->contents ?></td>
						</tr>
<?php	} ?>
					</tbody>
				</table>
			</div>
		</form>
	</body>
</html>

<script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<script>
	function search_bible(testament) {
		jQuery.ajax({
			url: "/bible/searchBible",
			type: "POST",
			dataType: "json",
			async: true,
			data: {testament : testament},
			success: function(proc_result) {
				document.getElementById("bible").innerHTML = proc_result.bible_html;
				document.getElementById("chapter").innerHTML = proc_result.chapter_html;
				document.getElementById("verse").innerHTML = proc_result.verse_html;
			}
		});
	}

	function search_chapter(bible_short) {
		document.getElementById("bible_input").value = bible_short;
		jQuery.ajax({
			url: "/bible/searchChapter",
			type: "POST",
			dataType: "json",
			async: true,
			data: {bible_short : bible_short},
			success: function(proc_result) {
				document.getElementById("chapter").innerHTML = proc_result.chapter_html;
				document.getElementById("verse").innerHTML = proc_result.verse_html;
			}
		});
	}

	function search_verse(chapter) {
		bible_short = document.getElementById("bible_input").value;
		jQuery.ajax({
			url: "/bible/searchVerse",
			type: "POST",
			dataType: "json",
			async: true,
			data: {bible_short : bible_short, chapter : chapter},
			success: function(proc_result) {
				document.getElementById("verse").innerHTML = proc_result.verse_html;
			}
		});
	}
</script>