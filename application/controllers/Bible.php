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
			$this->load->view("main");
			$this->log_model->logMessage("시험중");
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

		}

	}
?>