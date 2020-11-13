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
			<input type="hidden" id="testament" name="testament" value="구약">
			<input type="hidden" id="bible_short" name="bible_short" value="창">
			<input type="hidden" id="chapter" name="chapter" value="1">
			<input type="hidden" id="mode" name="mode" value="testament">
			<input type="hidden" id="search_text" name="search_text" value="구약">
			<div class="container">
				<div class="row">
					<div class="col-3">
						<button type="button" class="btn btn-info btn-block" onclick="search_bible('testament','구약')">구약</button>
					</div>
					<div class="col-3">
						<button type="button" class="btn btn-warning btn-block" onclick="search_bible('testament','신약')">신약</button>
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
						<select class="form-control" id="bible" name="bible" onchange="search_bible('bible', this.value)">
						</select>
					</div>
					<div class="col-5">
						<select class="form-control" id="chapter_select" name="chapter" onchange="search_bible('chapter', this.value)">
						</select>
					</div>
				</div>
				<div class="row"></row>
				<table class="table table-condensed table-hover" id="verse">
				</table>
			</div>
		</form>
	</body>
</html>

<script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<script>
	function search_bible(mode, search_text) {
		document.getElementById("mode").value = mode;
		document.getElementById("search_text").value = search_text;
		$.ajax({
			url: "/bible/searchBible",
			type: "POST",
			dataType: "json",
			async: true,
			data: $("#frm").serialize(),
			success: function(proc_result) {
				document.getElementById("testament").value = proc_result.testament;
				document.getElementById("bible_short").value = proc_result.bible_short;
				document.getElementById("chapter").value = proc_result.chapter;
				document.getElementById("bible").innerHTML = proc_result.bible_html;
				document.getElementById("chapter_select").innerHTML = proc_result.chapter_html;
				document.getElementById("verse").innerHTML = proc_result.verse_html;
			}
		});
	}

	search_bible("testament", "구약"); // 최초 실행시 처음 성경책 보여주기
</script>