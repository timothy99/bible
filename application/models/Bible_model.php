<?php
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Bible_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model("query_model");
		}

		/**
		 * @author 배진모
		 * @see 성경책 텍스트 파일의 처리
		 * @param null
		 * @return array $proc_result
		 */
		public function procBibleText($data)
		{
			$this->db->trans_begin();

			$file_path = $data["file_path"];
			$testament = $data["testament"];
			$bible_name = $data["bible_name"];
			$bible_short = $data["bible_short"];

			$file = fopen($file_path,"r");
			while(!feof($file)) {
				$line = fgets($file, 4096);
				$line = str_replace("'","&#39;",$line); // '(어퍼스트로피) 가 글자 사이에 들어가서 이거 처리함
				$line = str_replace("\"","&#34;",$line); // 쌍따옴표는 지움
				$line = str_replace(",","&#44;",$line); // 콤마 들어간 것들 처리
				$line = str_replace("\\","&#92;",$line); // 역슬래시 들어간 것들 처리
				$line = str_replace("\n","",$line); // 줄바꿈 삭제

				$line_length = strlen($line);
				if($line_length > 0) { // 텍스트가 1자 이상 있어야지만 입력
					$chapter_position = strpos($line," "); // 장절 텍스트를 찾는다 (보통 제일 앞에 최초 공백까지)
					$chapter_text = substr($line, 0, $chapter_position); // 찾은 위치 기준으로 장절 텍스트 분리
					$contents = trim(substr($line, $chapter_position)); // 뒤의 내용 텍스트를 분리해내고 앞뒤 공백을 제거한다
					$title_position = strpos($contents,">");
					if($title_position > 0) {
						$title = trim(substr($contents, 1, $title_position-1)); // 소제목을 뽑아낸다
						$contents = trim(str_replace("<".$title.">", "", $contents)); // 소제목이 있는 경우에는 소제목을 제거한 내용을 만든다
					} else {
						$title = null;
					}
					$chapter_text = str_replace($bible_short, "", $chapter_text);
					$chapter_arr = explode(":", $chapter_text);
					$chapter = $chapter_arr[0];
					$verse = $chapter_arr[1];
					
					$db_table = "bible";

					$db_column = array();
					array_push($db_column, array("testament", $testament, TRUE));
					array_push($db_column, array("bible_name", $bible_name, TRUE));
					array_push($db_column, array("bible_short", $bible_short, TRUE));
					array_push($db_column, array("chapter", $chapter, TRUE));
					array_push($db_column, array("verse", $verse, TRUE));
					array_push($db_column, array("title", $title, TRUE));
					array_push($db_column, array("contents", $contents, TRUE));

					$db_data = array();
					$db_data["db_table"] = $db_table;
					$db_data["db_column"] = $db_column;
					$db_result = $this->query_model->dbInsert($db_data);
				}
			}
			fclose($file);

			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$result = false;
				$message = "성경책 입력에 실패했습니다";
			} else {
				$this->db->trans_commit();
				$result = true;
				$message = "성경책 입력을 완료했습니다";
			}

			$proc_result = array();
			$proc_result["result"] = $result;
			$proc_result["message"] = $message;

			return $proc_result;
		}

		/**
		 * @author 배진모
		 * @see 성경책 텍스트 파일을 기준으로 성경책 목록 갖고 오기
		 * @param file_name
		 * @return array $proc_result
		 */
		public function getBibleName($file_name)
		{
			$result = false;
			$message = "검색결과가 없습니다";
			$bible_name = null;
			$bible_short = null;
			$testament = null;

			$bible_list = $this->getBibleList("전체"); // 성경책 전체 목록 갖고 오기
			foreach($bible_list as $no => $val) {
				$search_bible_name = $val->bible_name;
				$search_position = strpos($file_name, $search_bible_name);
				if($search_position > 0) {
					$testament = $val->testament;
					$bible_name = $val->bible_name;
					$bible_short = $val->bible_short;
					$result = true; 
					$message = "결과를 보여줍니다.";
				}
			}

			$proc_result = array();
			$proc_result["result"] = $result;
			$proc_result["message"] = $message;
			$proc_result["testament"] = $testament;
			$proc_result["bible_name"] = $bible_name;
			$proc_result["bible_short"] = $bible_short;

			return $proc_result;
		}

		/**
		 * @author 배진모
		 * @see 성경책 검색 결과 보여주기
		 * @param data
		 * @return array
		 */
		public function getVerseList($data)
		{
			$result = true;
			$message = "찾기 완료";

			$bible_short = $data["bible_short"];
			$chapter = $data["chapter"];

			$db_select = array();
			array_push($db_select, array("*", TRUE));

			$db_where = array();

			array_push($db_where, ["bible_short", $bible_short, TRUE]);
			array_push($db_where, ["chapter", $chapter, TRUE]);

			$db_data["db_table"] = "bible";
			$db_data["db_select"] = $db_select;
			$db_data["db_where"] = $db_where;

			$db_result = $this->query_model->dbList($db_data);

			$proc_result = array();
			$proc_result["result"] = $result;
			$proc_result["message"] = $message;
			$proc_result["verse_list"] = $db_result["db_list"];

			return $proc_result;
		}

		/**
		 * @author 배진모
		 * @see 성경책 장(chapter) 결과 보여주기
		 * @param data
		 * @return array
		 */
		public function getChapterList($data)
		{
			$result = true;
			$message = "찾기 완료";

			$bible_short = $data["bible_short"];
			$chapter = $data["chapter"];

			$db_select = array();
			array_push($db_select, array("*", TRUE));

			$db_where = array();
			array_push($db_where, ["bible_short", $bible_short, TRUE]);

			$db_data["db_table"] = "search";
			$db_data["db_select"] = $db_select;
			$db_data["db_where"] = $db_where;

			$db_result = $this->query_model->dbView($db_data);
			$db_view = $db_result["db_view"];
			$chapter_arr = explode("|", $db_view->chapters);
			$chapter_list = array();
			foreach($chapter_arr as $no => $array_chapter) {
				if($array_chapter == $chapter) {
					$selected = "selected";
				} else {
					$selected = "";
				}
				$chapter_info = new stdClass();
				$chapter_info->chapter = $array_chapter;
				$chapter_info->selected = $selected;
				array_push($chapter_list, $chapter_info);
			}

			$proc_result = array();
			$proc_result["result"] = $result;
			$proc_result["message"] = $message;
			$proc_result["chapter_list"] = $chapter_list;

			return $proc_result;
		}

		/**
		 * @author 배진모
		 * @see 성경책 목록 갖고 오기
		 * @param $mode - 검색조건 (전체, 신약, 구약)
		 * @return array $proc_result
		 */
		public function getBibleList($data)
		{
			$result = true;
			$message = "찾기 완료";

			$mode = $data["testament"];
			$bible_short = $data["bible_short"];
			if($mode == "전체") {
				// 아무것도 안함
			} else {
				$db_where = array();
				array_push($db_where, ["testament", $mode, TRUE]);
			}

			$db_select = array();
			array_push($db_select, array("*", TRUE));

			$db_data["db_table"] = "search";
			$db_data["db_select"] = $db_select;
			$db_data["db_order"] = "search_idx";
			if($db_where != null) {
				$db_data["db_where"] = $db_where;
			}
			$db_result = $this->query_model->dbList($db_data);
			$bible_list = $db_result["db_list"];
			
			foreach($bible_list as $no => $val) {
				if($val->bible_short == $bible_short) {
					$selected = "selected";
				} else {
					$selected = "";
				}
				$bible_list[$no]->selected = $selected;
			}

			$proc_result = array();
			$proc_result["result"] = $result;
			$proc_result["message"] = $message;
			$proc_result["bible_list"] = $bible_list;
			

			return $proc_result;

		}

		/**
		 * @author 배진모
		 * @see 성경책 목록 갖고 오기
		 * @param null
		 * @return array $proc_result
		 */
		public function chapterUpdate()
		{
			$result = true;
			$message = "입력완료";

			$sql = "select a.testament, a.bible_short, GROUP_CONCAT(distinct a.chapter order by a.chapter asc separator '|') as chapters from bible a group by a.testament, a.bible_short";
			$chapter_list = $this->query_model->dbListQuery($sql);
			foreach($chapter_list as $no => $val) {
				$testament = $val->testament;
				$bible_short = $val->bible_short;
				$chapters = $val->chapters;

				$db_column = array();
				array_push($db_column, array("chapters", $chapters, TRUE));

				$db_where = array();
				array_push($db_where, array("testament", $testament, TRUE));
				array_push($db_where, array("bible_short", $bible_short, TRUE));

				$db_data = array();
				$db_data["db_table"] = "search";
				$db_data["db_column"] = $db_column;
				$db_data["db_where"] = $db_where;
				$db_result = $this->query_model->dbUpdate($db_data);
			}

			$proc_result = array();
			$proc_result["result"] = $result;
			$proc_result["message"] = $message;

			return $proc_result;
		}


	}
?>