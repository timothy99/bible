<?php
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Bible extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model("log_model");
			$this->load->model("bible_model");
			$this->load->model("file_model");
		}

		public function index()
		{
			$this->main();
		}

		/**
		 * @author 배진모
		 * @see 성경책 메인함수
		 * @param null
		 * @return void
		 */
		public function main()
		{
			// 최초 로딩시
			$data = array();
			$data["testament"] = "구약";
			$data["bible_short"] = "창";
			$data["chapter"] = "1";
			$proc_result = $this->bible_model->getVerseList($data);
			$verse_list = $proc_result["verse_list"];
			$proc_result = $this->bible_model->getBibleList($data["testament"]);
			$bible_list = $proc_result["bible_list"];
			$proc_result = $this->bible_model->getChapterList($data);
			$chapter_list = $proc_result["chapter_list"];

			$proc_result = array();
			$proc_result["result"] = true;
			$proc_result["message"] = "성경을 잘 보여줍니다";
			$proc_result["data"] = $data;
			$proc_result["bible_list"] = $bible_list;
			$proc_result["verse_list"] = $verse_list;
			$proc_result["chapter_list"] = $chapter_list;

			$this->load->view("main", $proc_result);
		}

		/**
		 * @author 배진모
		 * @see 구약 신약 눌렀을때 성경의 리스트 찾아오고 최초 성경의 첫장으로 데이터 로딩
		 * @param testament
		 * @return json_array
		 */
		public function searchBible()
		{
			$testament = $this->input->post("testament", TRUE) == null ? null : $this->input->post("testament", TRUE);
			$data = array();
			$data["testament"] = $testament;
			if($testament == "구약") {
				$data["bible_short"] = "창";
			} else {
				$data["bible_short"] = "마";
			}
			$data["chapter"] = "1";

			$proc_result = $this->bible_model->getBibleList($testament);
			$bible_list = $proc_result["bible_list"];
			$proc_result = $this->bible_model->getChapterList($data);
			$chapter_list = $proc_result["chapter_list"];
			$proc_result = $this->bible_model->getVerseList($data);
			$verse_list = $proc_result["verse_list"];

			$proc_result = array();
			$proc_result["result"] = true;
			$proc_result["message"] = "성경을 잘 보여줍니다";
			$proc_result["data"] = $data;
			$proc_result["bible_list"] = $bible_list;
			$proc_result["chapter_list"] = $chapter_list;
			$proc_result["verse_list"] = $verse_list;

			$bible_html = $this->load->view("bible", $proc_result, TRUE);
			$chapter_html = $this->load->view("chapter", $proc_result, TRUE);
			$verse_html = $this->load->view("verse", $proc_result, TRUE);

			$html_result = array();
			$html_result["bible_html"] = $bible_html;
			$html_result["chapter_html"] = $chapter_html;
			$html_result["verse_html"] = $verse_html;

			echo json_encode($html_result);
		}

		/**
		 * @author 배진모
		 * @see 성경을 눌렀을때 장을 찾아오고 성경의 첫장의 내용을 화면에 보여줌
		 * @param testament
		 * @return json_array
		 */
		public function searchChapter()
		{
			$bible_short = $this->input->post("bible_short", TRUE) == null ? null : $this->input->post("bible_short", TRUE);
			$data = array();
			$data["bible_short"] = $bible_short;
			$data["chapter"] = "1";

			$proc_result = $this->bible_model->getChapterList($data);
			$chapter_list = $proc_result["chapter_list"];
			$proc_result = $this->bible_model->getVerseList($data);
			$verse_list = $proc_result["verse_list"];

			$proc_result = array();
			$proc_result["result"] = true;
			$proc_result["message"] = "성경을 잘 보여줍니다";
			$proc_result["data"] = $data;
			$proc_result["chapter_list"] = $chapter_list;
			$proc_result["verse_list"] = $verse_list;

			$chapter_html = $this->load->view("chapter", $proc_result, TRUE);
			$verse_html = $this->load->view("verse", $proc_result, TRUE);

			$html_result = array();
			$html_result["chapter_html"] = $chapter_html;
			$html_result["verse_html"] = $verse_html;

			echo json_encode($html_result);
		}

		/**
		 * @author 배진모
		 * @see 성경을 눌렀을때 장을 찾아오고 성경의 첫장의 내용을 화면에 보여줌
		 * @param testament
		 * @return json_array
		 */
		public function searchVerse()
		{
			$bible_short = $this->input->post("bible_short", TRUE) == null ? null : $this->input->post("bible_short", TRUE);
			$chapter = $this->input->post("chapter", TRUE) == null ? null : $this->input->post("chapter", TRUE);
			$data = array();
			$data["bible_short"] = $bible_short;
			$data["chapter"] = $chapter;

			$proc_result = $this->bible_model->getVerseList($data);
			$verse_list = $proc_result["verse_list"];

			$proc_result = array();
			$proc_result["result"] = true;
			$proc_result["message"] = "성경을 잘 보여줍니다";
			$proc_result["data"] = $data;
			$proc_result["verse_list"] = $verse_list;

			$verse_html = $this->load->view("verse", $proc_result, TRUE);

			$html_result = array();
			$html_result["verse_html"] = $verse_html;

			echo json_encode($html_result);
		}

		/**
		 * @author 배진모
		 * @see 성경책 텍스트 파일의 처리
		 * @param null
		 * @return void
		 */
		public function procBibleText()
		{
			$base_directory = "/volume1/web/bible/txt"; // 성경 텍스트가 들어있는 디렉토리
			$directory_list = $this->file_model->getDirectoryList($base_directory, "file"); // 파일로 된 리스트만 가져오기
			$file_list = $directory_list["file_list"]; // 파일리스트만 뽑아오기
			foreach($file_list as $no => $file_name) { // 파일명대로 반복문
				$proc_result = $this->bible_model->getBibleName($file_name); // 파일명 기준으로 입력해야할 성경책의 정보 갖고오기
				$data = array();
				$data["file_path"] = $base_directory."/".$file_name;
				$data["testament"] = $proc_result["testament"];
				$data["bible_name"] = $proc_result["bible_name"];
				$data["bible_short"] = $proc_result["bible_short"];
				$proc_result = $this->bible_model->procBibleText($data); // 성경파일 처리
			}

			// 처리된 성경책을 기준으로 장 수를 구한다음 그걸 검색용으로 데이터 입력
			$proc_result = $this->bible_model->chapterUpdate();
		}

	}
?>